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

            $threads[] = $thread;
        }

        return $threads;
    }

    public function get_thread(Request $request, $id) 
    {
        $thread = \App\Thread::where('id', $id)->first();

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
