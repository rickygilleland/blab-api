<?php

namespace App\Broadcasting;

use App\User;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


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

        $this->streaming_backend_api_url = config('services.streaming_backend.url');
        $this->streaming_backend_api_secret = config('services.streaming_backend.secret');
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

                    //get the room details from the backend server

                    $data = [
                        "janus" => "create", 
                        "transaction" => Str::random(80), 
                        "apisecret" => $this->streaming_backend_api_secret
                    ];

                    //create a session
                    $session_handler = Http::post($this->streaming_backend_api_url, $data);
                    $session_handler = $session_handler->json();
                    $session_handler = $session_handler['data']['id'];

                    $data = [
                        "janus" => "attach", 
                        "plugin" => "janus.plugin.videoroom", 
                        "transaction" => Str::random(80), 
                        "apisecret" => $this->streaming_backend_api_secret
                    ];

                    $api_url_with_handler = $this->streaming_backend_api_url . "/" . $session_handler;

                    //attach the video room plugin
                    $room_handler = Http::post($api_url_with_handler, $data);
                    $room_handler = $room_handler->json();
                    //$room_handler = $room_handler['data']['id'];

                    $message_body = [
                        "request" => "exists",
                        "room" => $room->channel_id
                    ];  

                    $data = [
                        "janus" => "message", 
                        "body" => $message_body,
                        "transaction" => Str::random(80), 
                        "apisecret" => $this->streaming_backend_api_secret
                    ];

                    $room_exists = Http::post($api_url_with_handler, $data);
                    $room_exists = $room_exists->json();


                    //rooms can be fetched or created via messages -- ["janus" => "message", "body" => $message_array, "transaction", "apisecret"]

                    //check if room exists
                /* {
                        "request" : "exists",
                        "room" : <unique numeric ID of the room to check>
                    }*/

                    //if not, create -- otherwise 

                    /*
                    "request" : "create",
                    "room" : <unique numeric ID, optional, chosen by plugin if missing>,
                    "permanent" : <true|false, whether the room should be saved in the config file, default=false>,
                    "description" : "<pretty name of the room, optional>",
                    "secret" : "<password required to edit/destroy the room, optional>",
                    "pin" : "<password required to join the room, optional>",
                    "is_private" : <true|false, whether the room should appear in a list request>,
                    "allowed" : [ array of string tokens users can use to join this room, optional],
                    */

                    return [
                        'id' => $user->id, 
                        'name' => $user->name, 
                        'avatar' => $user->avatar_url, 
                        'peer_uuid' =>  Str::uuid(), 
                        'nts_user' => $token->username, 
                        'nts_password' => $token->password,
                        'streamer_session' => $session_handler,
                        'room_handler' => $room_handler,
                        'room_exists' => $room_exists
                    ];
                }
            }
        }
    }
}
