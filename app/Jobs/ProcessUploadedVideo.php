<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Events\DirectMessageUpdated;
use App\Events\LibraryItemUpdated;
use App\Attachment;

use FFMpeg;
use Log;

class ProcessUploadedVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $attachment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //$attachment = \App\Attachment::find($this->attachment)->with(['organization', 'messages', 'libraryItem']);

        Log::info("Attachment", $this->attachment);

        $converted_video = FFMpeg::fromDisk('spaces')->open($this->attachment->path);

        try {
            $converted_video->export()
                ->toDisk('spaces')
                ->inFormat(new \FFMpeg\Format\Video\X264('aac'))
                ->withVisibility('private')
                ->save(str_replace('.webm', '.mp4', $this->attachment->path));

        } catch (\Illuminate\Contracts\Filesystem\FileExistsException $e) {
            //this is fine, let it try updating the model anyways
        }

        Log::info("Video converted");

        $this->attachment->path = str_replace('.webm', '.mp4', $this->attachment->path);
        $this->attachment->mime_type = "video/mp4";
        $this->attachment->processed = true;
        $this->attachment->temporary_url = Storage::temporaryUrl(
            $attachment->path, now()->addDays(7)
        );
        $this->attachment->temporary_url_last_updated = Carbon::now();
        $this->attachment->save();

        Log::info("Attachment updated", $this->attachment);

        $thumbnail_path = 'message_thumbnails/' . $this->attachment->id . "_" . uniqid() . '.jpg';

        $converted_video->getFrameFromSeconds(1)
            ->export()
            ->toDisk('spaces')
            ->withVisibility('public')
            ->save($thumbnail_path);

        $this->attachment->thumbnail_path = $thumbnail_path;
        $this->attachment->thumbnail_temporary_url = Storage::url($thumbnail_path);
        $this->attachment->save();

        Log::info("Thumbnail generated", $this->attachment);

        Log::info("Messages relationship", $this->attachment->messages);
        if ($this->attachment->messages != null) {
            foreach ($this->attachment->messages as $message) {
                $updated_message = \App\Message::where('id', $message->id)->with(['thread', 'user', 'organization', 'attachments'])->first();

                $notification = new \stdClass;
                $notification->triggered_by = $message->user_id;
                $notification->message = $updated_message;
                $notification->thread = $updated_message->thread;
        
                foreach($message->thread->users as $thread_user) {
                    $notification->recipient_id = $thread_user->id;
                    broadcast(new DirectMessageUpdated($notification));
                }
            }
        }

        Log::info("LibraryItem relationship", $this->attachment->libraryItem);

        if ($this->attachment->libraryItem != null) {

            $library_item = \App\LibraryItem::where('id', $this->attachment->libraryItem)->with('attachment.user')->first();

            $notification = new \stdClass;
            $notification->triggered_by = $library_itemL->created_by_user;
            $notification->item = $library_item;
            $notification->recipient_id = $library_item->created_by_user;

            broadcast(new LibraryItemUpdated($notification));
        }
        
    }
}
