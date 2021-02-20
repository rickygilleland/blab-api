<?php

namespace App\Listeners;

use Log;

use BeyondCode\LaravelWebSockets\Events\UnsubscribedFromChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Socket;
use App\User;

use App\Events\UserJoinedRoom;

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
            $socket = Socket::where('socketId', $event->socketId)->first();

            if ($socket) {
                $user = User::where('id', $socket->user_id)->first();

                if ($user) {
                    $user->current_room_id = null;
                    $user->save();

                    $notification = new \stdClass;
                    $notification->room = $room;
                    $notification->user = $user;

                    broadcast(new UserLeftRoom($notification));
                }
            }
        }
    }
}
