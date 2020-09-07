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
        $message = \App\Message::where('id', $id)->first();

        return $message;
    }

    public function create_message(Request $request)
    {
        $user = \Auth::user()->load('threads', 'organization.users');

        if ($user->organization->id != $request->organization_id) {
            abort(500);
        }

        //make sure all of the recipients are part of the org
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
        
        $message = new \App\Message();
        $message->user_id = $user->id;
        $message->organization_id = $request->organization_id;
        $message->is_public = $request->is_public === 'true' ? true : false;

        $active_thread = null;

        if (count($request->recipient_ids) > 0) {

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
                $attachment_url = Storage::disk('spaces')->putFile('message_attachments', $request->file('attachment'), 'private');
                $message->attachment_url = $attachment_url;
            } catch (\Exception $e) {
                //do something
            }
        }

        $message->slug = Str::random(12);

        $message->save();

        $message->thread = $active_thread;
        $message->load('user');

        $notification = new \stdClass;
        $notification->triggered_by = $user->id;
        $notification->message = $message;
        $notification->thread = $active_thread;

        foreach($active_thread->users as $thread_user) {
            /*if ($thread_user->id == $user->id) {
                continue;
            }*/

            $notification->recipient_id = $thread_user->id;
            broadcast(new NewDirectMessageSent($notification));
        }

        return $message;
        
    }

    public function edit_message(Request $request, $id)
    {
        
    }

}
