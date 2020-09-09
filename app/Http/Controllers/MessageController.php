<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\NewDirectMessageSent;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function get_message(Request $request, $id)
    {
        $message = \App\Message::where('id', $id)->load('organization', 'user')->first();

        $message->attachment_url = Storage::temporaryUrl(
            $message->attachment_path, now()->addDays(2)
        );

        if ($message->is_public) {
            $message->public_url = "https://blab.to/b/" . $message->organization->slug . "/" . $message->slug;
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
            'attachment' => 'nullable|mimes:wav'
        ]);
        
        $message = new \App\Message();
        $message->user_id = $user->id;
        $message->organization_id = $request->organization_id;
        $message->is_public = $request->is_public === 'true' ? true : false;

        $active_thread = null;

        if (isset($request->recipient_ids) && count($request->recipient_ids) > 0) {

            if (count($request->recipient_ids) == 1) {
                foreach($user->threads as $thread) {
                    if ($thread->users->contains($request->recipient_ids[0])) {
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

        if ($request->hasFile('attachment')) {
            try {
                $attachment_path = Storage::disk('spaces')->putFile('message_attachments', $request->file('attachment'), 'private');
                $message->attachment_path = $attachment_path;
                $message->attachment_mime_type = $request->file('attachment')->getMimeType();
            } catch (\Exception $e) {
                //do something
            }
        }

        $message->slug = Str::random(12);

        $message->save();

        $newMessage = \App\Message::where('id', $message->id)->with('user', 'thread')->first()->toArray();

        $newMessage["attachment_url"] = Storage::temporaryUrl(
            $newMessage["attachment_path"], now()->addDays(2)
        );

        if ($message->is_public) {
            $newMessage["public_url"] = "https://blab.to/b/" . $message->organization->slug . "/" . $message->slug;
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

    public function show(Request $request, $organization_slug, $blab_slug) 
    {

        if ($organization_slug == null || $blab_slug == null) {
            abort(404);
        }

        $organization = \App\Organization::where('slug', $organization_slug)->first();

        if (!$organization) {
            abort(404);
        }

        $message = \App\Message::where('slug', $blab_slug)->with('user')->first();

        if (!$message || $message->organization_id != $organization->id || !$message->is_public) {
            abort(404);
        }

        $message->attachment_url = Storage::temporaryUrl(
            $message->attachment_path, now()->addDays(2)
        );

        return view('message.index', ['message' => $message]);

    }
    
}
