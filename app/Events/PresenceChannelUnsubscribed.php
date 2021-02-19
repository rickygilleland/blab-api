<?php

namespace App\Listeners;

use BeyondCode\LaravelWebSockets\Events\UnsubscribedFromChannel;
use Illuminate\Contracts\Queue\ShouldQueue;

class PresenceChannelUnsubscribed implements ShouldQueue
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
        if (strpos($event->channelName, 'presence-room') !== false) {
            $user->current_room_id = null;
            $user->save();
        }
    }
}
