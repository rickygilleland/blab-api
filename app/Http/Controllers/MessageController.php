<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\NewMessageSentInRoom;

class MessageController extends Controller
{
    public function get_user_messages(Request $request)
    {

    }

    public function get_message(Request $request, $id)
    {
        
    }

    public function create_message(Request $request)
    {
        $user = \Auth::user();
        
        $message = new \App\Message();
        $message->user_id = $user->id;
        $message->organization_id = $request->organization_id;
        $message->is_public = $request->is_public;
        $message->recipient_id = null;

        if ($request->is_public == false) {
            $message->recipient_id = $request->recipient_id;
        }

        if ($request->hasFile('attachment')) {
            try {
                $attachment_url = Storage::disk('spaces')->putFile('message_attachments', $request->file('attachment'), 'private');
                $message->attachment_url = "https://blab.sfo2.cdn.digitaloceanspaces.com/" . $attachment_url;
            } catch (\Exception $e) {
                //do something
            }
        }

        $message->slug = Str::random(12);

        $message->save();

        $notification = new \stdClass;
        $notification->triggered_by = $user->id;
        $notification->recipient_id = $message->recipient_id;
        $notification->message = $message;

        broadcast(new NewMessageSentInRoom($notification));
        
    }

    public function edit_message(Request $request, $id)
    {
        
    }

}
