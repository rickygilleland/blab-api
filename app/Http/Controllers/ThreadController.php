<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function get_user_threads(Request $request) 
    {
        $user = \Auth::user();
        $threads = [];

        foreach($user->threads as $thread) {
            if ($thread->teams->contains($request->team_id)) {
                $threads[] = $thread;
            }
        }

        return $threads;
    }

    public function get_thread(Request $request, $id) 
    {
        $thread = \App\Thread::where('id', $id)->first();
        return $thread;
    }

    public function get_messages(Request $request, $id) 
    {
        $thread = \App\Thread::where('id', $id)->first();
        return $thread->messages;
    }

}
