<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function get_user_threads(Request $request) 
    {
        $user = \Auth::user();

        $threads = [];
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

            $threads[] = $thread;
        }

        return $threads;
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
        $thread = \App\Thread::where('id', $id)->with('messages')->first();
        return $thread;
    }

}
