<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThreadController extends Controller
{
    public function get_user_threads(Request $request) 
    {
        $user = \Auth::user();

        $private_threads = [];
        $public_threads = [];
        $shared_threads = [];

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

            $private_threads[] = $thread;
        }

        if (count($public_threads) == 0) {
            $thread = new \App\Thread();
            $thread->slug = Str::random(12);
            $thread->type = "public";
            $thread->save();

            $user->threads()->attach($thread);

            $public_threads[] = $thread;
        }

        $response = new \stdClass(); 
        $response->private_threads = $private_threads;
        $response->public_threads = $public_threads;
        $response->shared_threads = $shared_threads;

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
            $message->attachment_url = Storage::temporaryUrl(
                $message->attachment_url, now()->addDays(2)
            );
        }

        return $thread;
    }

}
