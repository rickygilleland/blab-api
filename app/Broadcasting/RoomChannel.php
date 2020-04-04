<?php

namespace App\Broadcasting;

use App\User;

class RoomChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */

    protected $sid;
	protected $token;
	protected $key;
    protected $secret;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
		$this->token = config('services.twilio.token');
		$this->key = config('services.twilio.key');
        $this->secret = config('services.twilio.secret');
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @return array|bool
     */
    public function join(User $user, $channelId)
    {
        foreach ($user->teams as $team) {
            foreach ($team->rooms as $room) {
                if ($room->channel_id == $channelId) {
                    //generate them a twilio nts token as well
                    $twilio = new \Twilio\Rest\Client($this->sid, $this->token);

                    $token = $twilio->tokens->create();

                    return ['id' => $user->id, 'name' => $user->name, 'avatar' => $user->avatar_url, 'peer_uuid' =>  Str::uuid(), 'ice_servers' => $token->ice_servers];
                }
            }
        }
    }
}
