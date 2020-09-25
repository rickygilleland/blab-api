<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ThreadController extends Controller
{
    public function get_user_threads(Request $request) 
    {
        $user = \Auth::user();

        $private_threads = [];
        $public_threads = [];
        $shared_threads = [];
        $room_threads = [];

        foreach ($user->threads as $thread) {

            $thread_users = [];

            foreach ($thread->users as $thread_user) {
                if ($thread_user->id != $user->id) {
                    $thread_user->makeHidden('streamer_key');
                    $thread_user->makeHidden('email');

                    $thread_users[] = $thread_user;
                }
            }

            $thread->unsetRelation('users');
            $thread->users = $thread_users;

            if ($thread->type == "public") {
                $public_threads[] = $thread;
                continue;
            }

            if ($thread->name == null) {
                $name = '';

                $i = 0;
                foreach ($thread->users as $thread_user) {
                    $name .= $thread_user->first_name;

                    if ($i != count($thread->users) - 1) {
                        $name .= ", ";
                    }

                    $i++;
                }

                $thread->name = $name;
            }

            if ($thread->type == "room") {
                $room_threads[] = $thread;
                continue;
            }

            $private_threads[] = $thread;
        }

        if (count($public_threads) == 0) {
            $thread = new \App\Thread();
            $thread->slug = Str::random(12);
            $thread->type = "public";
            $thread->save();

            $user->threads()->attach($thread);

            $thread->users;

            $public_threads[] = $thread;
        }

        $response = new \stdClass(); 
        $response->private_threads = $private_threads;
        $response->public_threads = $public_threads;
        $response->shared_threads = $shared_threads;
        $response->room_threads = $room_threads;

        return response()->json($response);
    }

    public function get_thread(Request $request, $id) 
    {
        $user = \Auth::user();
        $thread = \App\Thread::where('id', $id)->with('users')->first();

        if (!$thread->users->contains($user)) {
            abort(404);
        }

        $thread_users = [];

        foreach ($thread->users as $thread_user) {
            if ($thread_user->id != $user->id) {
                $thread_user->makeHidden('streamer_key');
                $thread_user->makeHidden('email');

                $thread_users[] = $thread_user;
            }
        }

        $thread->unsetRelation('users');
        $thread->users = $thread_users;

        if ($thread->name == null) {
            $name = '';

            $i = 0;
            foreach ($thread->users as $user) {
                $name .= $user->first_name;

                if ($i != count($thread->users) - 1) {
                    $name .= ", ";
                }
                
                $i++;
            }

            $thread->name = $name;
        }

        return $thread;
    }

    public function get_messages(Request $request, $id) 
    {
        $thread = \App\Thread::where('id', $id)->with(['messages.user', 'messages.attachments'])->first();

        if (!$thread) {
            abort(404);
        }

        foreach ($thread->messages as $message) {

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
        }

        return $thread;
    }

}
