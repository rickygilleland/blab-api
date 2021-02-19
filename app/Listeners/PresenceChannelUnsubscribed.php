<?php

namespace App\Listeners;

use Log;

use BeyondCode\LaravelWebSockets\Events\UnsubscribedFromChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class PresenceChannelUnsubscribed
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
     * @param  UnsubscribedFromChannel  $event
     * @return void
     */
    public function handle(UnsubscribedFromChannel $event)
    {
        Log::info(json_encode($event));

        if (strpos($event->channelName, 'presence-room') !== false) {
            $user->current_room_id = null;
            $user->save();
        }
    }
}
