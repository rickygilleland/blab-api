<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Events\DirectMessageUpdated;

use FFMpeg;

class ProcessUploadedVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $converted_video = FFMpeg::fromDisk('spaces')->open($this->message->attachment_path);

        $converted_video->export()
            ->toDisk('spaces')
            ->inFormat(new \FFMpeg\Format\Video\X264('aac'))
            ->withVisibility('private')
            ->save(str_replace('.webm', '.mp4', $this->message->attachment_path));

        $this->message->attachment_path = str_replace('.webm', '.mp4', $this->message->attachment_path);
        $this->message->attachment_mime_type = "video/mp4";
        $this->message->attachment_processed = true;
        $this->message->attachment_temporary_url_last_updated = null;
        $this->message->save();

        $thumbnail_path = 'message_thumbnails/' . $this->message->id . "_" . uniqid() . '.jpg';

        $converted_video->getFrameFromSeconds(1)
            ->export()
            ->toDisk('spaces')
            ->withVisibility('public')
            ->save($thumbnail_path);

        $this->message->attachment_thumbnail_path = $thumbnail_path;
        $this->message->attachment_thumbnail_temporary_url = Storage::url($thumbnail_path);
        $this->message->save();

        $message = \App\Message::where('id', $this->message->id)->with(['thread', 'user', 'organization'])->first();

        $notification = new \stdClass;
        $notification->triggered_by = $message->user_id;
        $notification->message = $message;
        $notification->thread = $message->thread;

        foreach($message->thread->users as $thread_user) {
            $notification->recipient_id = $thread_user->id;
            broadcast(new DirectMessageUpdated($notification));
        }
        
    }
}
