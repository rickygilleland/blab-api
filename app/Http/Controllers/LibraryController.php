<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Jobs\ProcessUploadedVideo;
use App\Jobs\TranscribeAudio;

class LibraryController extends Controller
{
    public function get_items(Request $request)
    {
        $user = \Auth::user()->load('libraryItems.attachment.user');

        return $user->libraryItems;
    }

    public function create_item(Request $request)
    {
        $request->validate([
            'attachment' => 'nullable|mimes:wav,webm,mp4',
            'attachment_id' => 'required_without:attachment'
        ]);

        $user = \Auth::user()->load('organization');

        if ($request->hasFile('attachment')) {

            try { 
                $attachment_path = Storage::disk('spaces')->putFile('message_attachments', $request->file('attachment'), 'private');

                $attachment = new \App\Attachment();
                $attachment->user_id = $user->id;
                $attachment->organization_id = $user->organization->id;
                $attachment->path = $attachment_path;
                $attachment->mime_type = $request->file('attachment')->getMimeType();
                $attachment->slug = Str::random(12);
                $attachment->processed = $attachment->mime_type == "audio/x-wav";

                if ($attachment->mime_type == "audio/x-wav") {
                    $attachment->temporary_url = Storage::temporaryUrl(
                        $attachment->path, now()->addDays(7)
                    );
                    $attachment->temporary_url_last_updated = Carbon::now();
                }

                $attachment->save();
            } catch (\Exception $e) {
                //do something
            }
        } else {
            $attachment = \App\Attachment::where('id', $request->attachment_id)->with('organization')->first();

            if (!$attachment || $attachment->organization->id != $user->organization->id) {
                abort(404);
            }
        }

        $library_item = new \App\LibraryItem();
        $library_item->created_by = $user->id;
        $library_item->attachment_id = $attachment->id;
        $library_item->save();

        $user->libraryItems()->attach($library_item);

        $library_item->attachment = $attachment;
        $attachment->user;

        if ($request->hasFile("attachment")) {
            if ($attachment->mime_type == "audio/x-wav") {
                //TranscribeAudio::dispatch($attachment);
            } else {
                ProcessUploadedVideo::dispatch($attachment); 
            }
        }

        return ($library_item);
    }
}