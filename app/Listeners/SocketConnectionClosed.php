<?php

namespace App\Listeners;

use Log;

use BeyondCode\LaravelWebSockets\Events\ConnectionClosed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Socket;


class SocketConnectionClosed
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
     * @param  ConnectionClosed  $event
     * @return void
     */
    public function handle(ConnectionClosed $event)
    {
        $socket = Socket::where('socketId', $event->socketId)->first();
        Log::info(json_encode($socket));

        if ($socket) {
            $socket->delete();
        }
    }
}
