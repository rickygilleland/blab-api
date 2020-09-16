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
        $thread = \App\Thread::where('id', $id)->with('messages.user')->first();

        if (!$thread) {
            abort(404);
        }

        foreach ($thread->messages as $message) {

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

            if ($message->is_public) {
                $message->public_url = "https://blab.to/b/" . $message->organization->slug . "/" . $message->slug;
            }
        }

        return $thread;
    }

}
