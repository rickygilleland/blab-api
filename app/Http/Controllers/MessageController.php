<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\NewDirectMessageSent;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Jobs\ProcessUploadedVideo;
use App\Jobs\TranscribeAudio;

class MessageController extends Controller
{
    public function get_message(Request $request, $id)
    {
        $message = \App\Message::where('id', $id)->load('organization', 'user', 'attachments')->first();
        $attachment_slug = null;

        if ($message->attachments != null) {
            foreach ($message->attachments as $attachment) {

                $last_updated = Carbon::parse($attachment->temporary_url_last_updated);

                if ($attachment->temporary_url_last_updated == null || $last_updated->diffInDays() > 5) {
                    $attachment->temporary_url = Storage::temporaryUrl(
                        $attachment->path, now()->addDays(7)
                    );
    
                    if ($attachment->thumbnail_path != null) {
                        $attachment->thumbnail_temporary_url = Storage::temporaryUrl(
                            $attachment->thumbnail_path, now()->addDays(7)
                        ); 
                    }
                }

                $attachment->save();

                $attachment_slug = $attachment->slug;

                //make the attachment changes backwards compatible for existing clients
                $message->attachment_processed = $attachment->processed;
                $message->attachment_mime_type = $attachment->mime_type;
                $message->attachment_temporary_url = $attachment->temporary_url;
                $message->attachment_thumbnail_url = $attachment->thumbnail_temporary_url;
            }
        }

        if ($message->is_public) {
            $message->public_url = "https://blab.to/b/" . $message->organization->slug . "/" . $attachment_slug;
        }

        return $message;
    }

    public function create_message(Request $request)
    {
        $user = \Auth::user()->load('threads', 'organization.users');

        if ($user->organization->id != $request->organization_id) {
            abort(500);
        }

        //make sure all of the recipients are part of the org
        if (isset($request->recipient_ids) && count($request->recipient_ids) > 0) {
            foreach ($request->recipient_ids as $recipient) {
                $found = false;  
    
                foreach($user->organization->users as $organization_user) {
                    if ($organization_user->id == $recipient) {
                        $found = true;
                    }
                }   
    
                if (!$found) {
                    abort(500);
                }
            }
        }

        $request->validate([
            'attachment' => 'nullable|mimes:wav,webm,mp4'
        ]);
        
        $message = new \App\Message();
        $message->user_id = $user->id;
        $message->organization_id = $request->organization_id;
        $message->is_public = $request->is_public === 'true' ? true : false;

        $active_thread = null;

        if (isset($request->recipient_ids) && count($request->recipient_ids) > 0 && !isset($request->thread_id)) {

            if (count($request->recipient_ids) == 1) {
                foreach($user->threads as $thread) {
                    if ($thread->type == "private" && $thread->users->contains($request->recipient_ids[0])) {
                        $active_thread = $thread;
                        break;
                    }
                }
            }

            if ($active_thread == null) {
                $active_thread = new \App\Thread();
                $active_thread->slug = Str::random(12);
                $active_thread->type = "private";
                $active_thread->save();

                $user->threads()->attach($active_thread);

                foreach ($request->recipient_ids as $recipient_id) {
                    $active_thread->users()->attach($recipient_id);
                }
            }

            $message->thread_id = $active_thread->id;
        }

        if ($message->is_public) {
            foreach ($user->threads as $thread) {
                if ($thread->type == "public") {
                    $message->thread_id = $thread->id;
                    $active_thread = $thread;
                }
            }

            if (!isset($message->thread_id)) {
                $active_thread = new \App\Thread();
                $active_thread->slug = Str::random(12);
                $active_thread->type = "public";
                $active_thread->save();

                $user->threads()->attach($active_thread);

                $message->thread_id = $active_thread->id;
                $message->save();
            }
        }

        if (isset($request->thread_id)) {
            if (!$user->threads->contains($request->thread_id)) {
                abort(404);
            }

            $active_thread = \App\Thread::where('id', $request->thread_id)->first();

            $message->thread_id = $request->thread_id;
        }

        if ($request->hasFile('attachment')) {
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

                $message->attachments()->attach($attachment);
        }

        $message->slug = Str::random(12);
        $message->save();

        $newMessage = \App\Message::where('id', $message->id)->with('user', 'thread', 'attachments')->first()->toArray();

        if ($message->is_public) {
            $newMessage["public_url"] = "https://blab.to/b/" . $message->organization->slug . "/" . $message->thread->slug . "/" . $message->slug;
        }

        if (isset($attachment)) {
            if ($attachment->mime_type == "audio/x-wav") {
                ProcessUploadedVideo::dispatch($attachment); 
            } else {
                TranscribeAudio::dispatch($attachment);
            }
        }

        $newMessage = (object)$newMessage;

        if (isset($attachment)) {
            //make the attachment changes backwards compatible for existing clients
            $newMessage->attachment_processed = $attachment->processed;
            $newMessage->attachment_mime_type = $attachment->mime_type;
            $newMessage->attachment_temporary_url = $attachment->temporary_url;
            $newMessage->attachment_thumbnail_url = $attachment->thumbnail_temporary_url;
        }

        $notification = new \stdClass;
        $notification->triggered_by = $user->id;
        $notification->message = (object)$newMessage;
        $notification->thread = $active_thread;

        foreach($active_thread->users as $thread_user) {
            if ($thread_user->id == $user->id) {
                continue;
            }

            $notification->recipient_id = $thread_user->id;
            broadcast(new NewDirectMessageSent($notification));
        }

        return $newMessage;
        
    }

    public function edit_message(Request $request, $id)
    {
        
    }

    public function show(Request $request, $organization_slug, $thread_slug, $blab_slug) 
    {

        if ($organization_slug == null || $blab_slug == null) {
            abort(404);
        }

        $organization = \App\Organization::where('slug', $organization_slug)->first();

        if (!$organization) {
            abort(404);
        }

        $message = \App\Message::where('slug', $blab_slug)->with('user', 'attachments')->first();

        if (!$message || $message->organization_id != $organization->id || !$message->is_public) {
            abort(404);
        }

        if ($message->attachment_path != null && $message->attachment_processed) {

            $attachment_url = $message->attachment_temporary_url;

            $update_attachment_temp_url = $message->attachment_temporary_url == null || $message->attachment_temporary_url_last_updated == null;

            if (!$update_attachment_temp_url && $message->attachment_thumbnail_path != null) {
                $last_updated = Carbon::parse($message->attachment_temporary_url_last_updated);

                $update_attachment_temp_url = $last_updated->diffInDays() > 5;
            }

            if ($update_attachment_temp_url) {

                $attachment_url = Storage::temporaryUrl(
                    $message->attachment_path, now()->addDays(7)
                );

                $message->attachment_temporary_url = $attachment_url;
                $message->attachment_temporary_url_last_updated = Carbon::now();

                $message->save();
            }
        }

        return view('message.index', ['message' => $message, 'organization_slug' =>  $organization_slug, 'blab_slug' => $blab_slug]);

    }
    
}
