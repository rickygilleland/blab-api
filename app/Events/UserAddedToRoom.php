<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAddedToRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $user;
    public $added_by;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->room = $notification->room;
        $this->user = $notification->user;
        $this->added_by = $notification->added_by;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $broadcast_channels = [];
        $broadcast_channels[] = new PresenceChannel('room.'.$this->room->channel_id);

        //keep broadcasting on organization channel for now until clients are updated -- removed in 1.2.0
        $broadcast_channels[] = new PresenceChannel('organization.'.$this->room->organization_id);

        if ($room->is_private == true) {
            $broadcast_channels[] = new PrivateChannel('user.'.$this->user->id);

            return $broadcast_channels;
        }
        
        return $broadcast_channels;
    }

    public function broadcastAs()
    {
        return 'room.user.invited';
    }
}
