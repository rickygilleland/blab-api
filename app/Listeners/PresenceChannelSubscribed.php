<?php

namespace App\Listeners;

use Log;

use BeyondCode\LaravelWebSockets\Events\SubscribedToChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Socket;
use App\User;
use App\Room;

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

        if (strpos($event->channelName, 'presence-room') !== false && $event->user !== null) {
            $socket = Socket::where('socketId', $event->socketId)->first();
            $user = User::where('id', $event->user->user_id)->first();

            Log::info($event->channelName);
            Log::info(json_encode($user));

            if (!$socket) {
                $socket = new Socket();
                $socket->socketId = $event->socketId;
                $socket->user_id = $event->user->user_id;
                $socket->save();
            }

            $channelId = explode('.', $event->channelName);

            $room = Room::where('channel_id', $$channelId[1])->first();

            Log::info($channelId[1]);

            if ($room) {
                $user->current_room_id = $room->id;
                $user->save();
            }
        }
    }
}
