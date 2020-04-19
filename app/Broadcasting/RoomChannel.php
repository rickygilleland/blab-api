<?php

namespace App\Broadcasting;

use App\User;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
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

                    //check if they already have a streamer key set
                    if ($user->streamer_key == null) {
                        $user->streamer_key = Hash::make(Str::random(256));
                        $user->streamer_key .= "_" . $user->id;
                        $user->save();
                    }

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
                    $room_handler = $room_handler['data']['id'];

                    $api_url_with_room_handler = $api_url_with_handler . "/" . $room_handler;

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

                    $room_exists = Http::post($api_url_with_room_handler, $data);
                    $room_exists = $room_exists->json();


                    if (!$room_exists['plugindata']['data']['exists']) {
                        //create the room
                        $message_body = [
                            "request" => "create",
                            "room" => $room->channel_id,
                            "secret" => md5($room->channel_id),
                            "is_private" => true,
                            "publishers" => 99,
                            "allowed" => [
                                $user->streamer_key
                            ]
                        ];  
    
                        $data = [
                            "janus" => "message", 
                            "body" => $message_body,
                            "transaction" => Str::random(80), 
                            "apisecret" => $this->streaming_backend_api_secret
                        ];

                        $room = Http::post($api_url_with_room_handler, $data);
                        $room = $room->json();
                    } else {
                        //make sure the current user's token is in there
                        $message_body = [
                            "request" => "allowed",
                            "secret" => md5($room->channel_id),
                            "action" => "add",
                            "allowed" => [
                                $user->streamer_key
                            ]
                        ];  
    
                        $data = [
                            "janus" => "message", 
                            "body" => $message_body,
                            "transaction" => Str::random(80), 
                            "apisecret" => $this->streaming_backend_api_secret
                        ];

                        $room = Http::post($api_url_with_room_handler, $data);
                        $room = $room->json();
                    }


                    return [
                        'id' => $user->id, 
                        'name' => $user->name, 
                        'avatar' => $user->avatar_url, 
                        'peer_uuid' =>  Str::uuid(), 
                        'nts_user' => $token->username, 
                        'nts_password' => $token->password,
                        'streamer_key' => $user->streamer_key,
                        'room' => $room,
                        'room_secret' => md5($room)
                    ];
                }
            }
        }
    }
}
