<?php

namespace App\Listeners;

use Log;

use BeyondCode\LaravelWebSockets\Events\SubscribedToChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class PresenceChannelSubscribed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscribedToChannel  $event
     * @return void
     */
    public function handle(SubscribedToChannel $event)
    {
        Log::info(json_encode($event));

        if (strpos($event->channelName, 'presence-room') !== false) {
            $user->current_room_id = null;
            $user->save();
        }
    }
}
