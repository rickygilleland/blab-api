<?php

namespace App\Broadcasting;

use App\User;
use App\Events\NotifyServerOutOfService;

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
    public function join(User $user, $channelId, $changeServer = false)
    {
        foreach ($user->teams as $team) {
            foreach ($team->rooms as $room) {
                if ($room->channel_id == $channelId) {
                    //check if they already have a streamer key set
                    if ($user->streamer_key == null) {
                        $user->streamer_key = Hash::make(Str::random(256));
                        $user->streamer_key .= "_" . $user->id; 
                        $user->save();
                    }

                    $server = null;

                    //make sure the selected server is still available
                    $server = \App\Server::where('id', $room->server_id)->first();

                    if (!$server->is_active) {
                        $changeServer = true;
                    }

                    if ($room->server_id == null || $changeServer == true) {

                        if ($user->timezone != null) {
                            if ($user->timezone == "America/New_York") {
                                $available_servers = \App\Server::where('is_active', 1)->where('location', 'us-east')->get();
                            } else {
                                $available_servers = \App\Server::where('is_active', 1)->where('location', 'us-west')->get();
                            }
                        }

                        if (!isset($available_servers) || count($available_servers) == 0) {
                            $available_servers = \App\Server::where('is_active', 1)->get();
                        }

                        if (count($available_servers) == 0) {
                            abort(503);
                        }

                        $least_loaded_key = 0;
                        $least_loaded_count = 0;
                        foreach ($available_servers as $key => $avail_server) {
                            $count = \App\Room::where('server_id', $avail_server->id)->count();

                            if ($count < $least_loaded_count) {
                                $least_loaded_key = $key;
                                $least_loaded_count = $count;
                            }
                        }

                        $room->server_id = $available_servers[$least_loaded_key]->id;
                        $room->save();

                        $server = $available_servers[$least_loaded_key]->hostname;
                    }

                    if ($server == null) {
                        $server = \App\Server::where('id', $room->server_id)->first();
                        $server = $server->hostname;
                    }
                    
                    if ($room->secret == null) {
                        $room->secret = Hash::make(Str::random(256));
                        $room->secret .= "_" . $room->id;
                        $room->save();
                    }

                    //get the room details from the backend server
                    $data = [
                        "janus" => "create", 
                        "transaction" => Str::random(80), 
                        "apisecret" => $this->streaming_backend_api_secret
                    ];

                    //create a session
                    try {

                        $session_handler = Http::post("https://".$server."/streamer", $data);
                        $session_handler = $session_handler->json();
                        $session_handler = $session_handler['data']['id'];

                        $data = [
                            "janus" => "attach", 
                            "plugin" => "janus.plugin.videoroom", 
                            "transaction" => Str::random(80), 
                            "apisecret" => $this->streaming_backend_api_secret
                        ];
    
                        $api_url_with_handler = "https://" . $server . "/streamer/" . $session_handler;
                        
    
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
    
    
                        if (!isset($room_exists['plugindata']['data']['exists']) || !$room_exists['plugindata']['data']['exists']) {
                            //create the room
                            $message_body = [
                                "request" => "create",
                                "admin_key" => $this->streaming_backend_api_secret,
                                "room" => $room->channel_id,
                                "secret" => $room->secret,
                                "is_private" => true,
                                "publishers" => 99,
                                "notify_joining" => true,
                                "videocodec" => "vp9",
                                "audiolevel_event" => true,
                                "audiolevel_ext" => true,
                                "video_svc" => true,
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
    
                            $streamer_room = Http::post($api_url_with_room_handler, $data);
                            $streamer_room = $streamer_room->json();
                        } else {
                            //make sure the current user's token is in there
                            $message_body = [
                                "request" => "allowed",
                                "admin_key" => $this->streaming_backend_api_secret,
                                "room" => $room->channel_id,
                                "secret" => $room->secret,
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
    
                            $streamer_room = Http::post($api_url_with_room_handler, $data);
                            $streamer_room = $streamer_room->json();
                        }
    
    
                        return [
                            'id' => $user->id, 
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name, 
                            'avatar' => $user->avatar_url, 
                            'peer_uuid' =>  md5($user->id), 
                            'streamer_key' => $user->streamer_key,
                            'timezone' => $user->timezone,
                            'media_server' => $server
                        ];

                    } catch(\Exception $e) {

                        //take this server out of service for now and try again
                        $server = \App\Server::where('hostname', $server)->first();
                        
                        if (!$server) {
                            return $this->join($user, $channelId, true);
                        }

                        $server->is_active = false;
                        $server->save();


                        $notification = new \stdClass;
                        $notification->triggered_by = $user->id;
                        $notification->room = $room;

                        broadcast(new NotifyServerOutOfService($notification))->toOthers();

                        //send an email alert
                        $sendgrid_key = env('SENDGRID_API_KEY');
                        $sg = new \SendGrid($sendgrid_key);

                        $email = new \SendGrid\Mail\Mail();
                        $email->setFrom("noreply@watercooler.work", "Water Cooler System");

                        $email->addTo("ricky@watercooler.work", "Ricky Gilleland");
                        $email->setSubject("A Media Server was Unreachable");
                        $email->addContent(
                            "text/html", $server->hostname . " was unavailable and has been placed out of service. 
                            Here's the error we received:<br>" . 
                            $e->getMessage()
                        );
                        
                        try {
                            $response = $sg->send($email);
                        } catch (Exception $e) {
                            //do something
                        }

                        return $this->join($user, $channelId, true);
                    }

                    
                }
            }
        }
    }
}
