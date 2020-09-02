<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewDirectMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $triggered_by;
    public $recipient_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->message = $notification->message;
        $this->triggered_by = $notification->triggered_by;
        $this->recipient_id = $notification->recipient_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->triggered_by);
        if ($this->message->is_public == false && $this->recipient_id) {
            return new PrivateChannel('user.'.$this->recipient_id);
        }
    }

    public function broadcastAs()
    {
        return 'user.messages.created';
    }
}
