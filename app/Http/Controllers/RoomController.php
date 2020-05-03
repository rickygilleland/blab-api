<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use TwilioRestClient;
use TwilioJwtAccessToken;
use TwilioJwtGrantsVideoGrant;

class RoomController extends Controller
{
    /**
     * Create a new controller instance.
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

    public function show($organization_slug, $team_slug, $room_slug)
    {   
        $user = \Auth::user();
        
        //fetch the rooms within the organization and make sure this checks out
        $organization = \App\Organization::where('slug', $organization_slug)->with('teams.rooms')->first();

        if (!$organization) {
            abort(404);
        }

        if ($user->organization->id != $organization->id) {
            abort(404);
        }

        $current_team = false;

        foreach ($organization->teams as $team) {
            if ($team->slug == $team_slug) {
                $current_team = $team;
            }
        }

        if (!$current_team) {
            abort(404);
        }

        $current_room = false;

        foreach ($current_team->rooms as $room) {
            if ($room->slug == $room_slug) {
                $current_room = $room;
            }
        }

        if (!$current_room) {
            abort(404);
        }

        $full_room_slug = $user->organization->slug.'/'.$current_team->slug.'/'.$current_room->slug;

        //setup the twilio video room and chat channel
        $client = new \Twilio\Rest\Client($this->sid, $this->token);

        $twilio_room_name = env('APP_ENV') . "_" . str_replace('/', '-', $full_room_slug);

        //check if this room already exists
        try {
            $client->video->rooms($twilio_room_name)->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $client->video->rooms->create([
                'uniqueName' => $twilio_room_name,
                'type' => 'peer-to-peer',
            ]);
        }
        
        $identity = env('APP_ENV') . "_" . $user->id;

        $token = new \Twilio\Jwt\AccessToken($this->sid, $this->key, $this->secret, 14400, $identity);

        $videoGrant = new \Twilio\Jwt\Grants\VideoGrant();
        $videoGrant->setRoom($twilio_room_name);

        $token->addGrant($videoGrant);

        $user->access_token = $token->toJWT();

        $room->twilio_room_name = $twilio_room_name;        
        
        return view('room.index', ['organization' => $organization, 'team' => $team, 'room' => $room, 'user' => $user ]);
    }

    public function create(Request $request) 
    {
        $user = \Auth::user();

        //validate the team id
        $team_found = false;
        $found_team = null;
        foreach ($user->teams as $team) {
            if ($team->id == $request->team_id) {
                $team_found = true;
                $found_team = $team;
            }
        }

        if (!$team_found) {
            abort(404);
        }

        //check the slug for uniqueness
        $room_slug = Str::slug($request->name, '-');

        $room = \App\Room::where('organization_id', $user->organization->id)->where('team_id', $request->team_id)->where('slug', $room_slug)->first();

        if ($room) {
            $room_slug = $room_slug . "-" . uniqid();
        }

        $room = new \App\Room();
        $room->name = $request->name;
        $room->team_id = $request->team_id;
        $room->organization_id = $user->organization->id;
        $room->slug = $room_slug;
        $room->is_private = $request->is_private;
        $room->video_enabled = $request->video_enabled;
        $room->channel_id = $user->organization->slug . "-" . $found_team->slug . "-" . $room_slug;
        $room->save();

        $room->secret = Hash::make(Str::random(256));
        $room->secret .= "_" . $room->id;
        $room->save();

        return $room;
    }

    public function getToken(Request $request)
    {
        $user = \Auth::user();

        $organization = \App\Organization::where('id', $user->organization->id)->with('teams.rooms')->first();

        $current_team = false;

        foreach ($organization->teams as $team) {
            if ($team->slug == $request->team_slug) {
                $current_team = $team;
            }
        }

        if (!$current_team) {
            abort(404);
        }

        $current_room = false;

        foreach ($current_team->rooms as $room) {
            if ($room->slug == $request->room_slug) {
                $current_room = $room;
            }
        }

        if (!$current_room) {
            abort(404);
        }
        
        $identity = env('APP_ENV') . "_" . $user->id;

        $full_room_slug = $user->organization->slug.'/'.$current_team->slug.'/'.$current_room->slug;

        //setup the twilio video room and chat channel
        $twilio = new \Twilio\Rest\Client($this->sid, $this->token);

        $twilio_room_name = env('APP_ENV') . "_" . str_replace('/', '-', $full_room_slug);

        $token = new \Twilio\Jwt\AccessToken($this->sid, $this->key, $this->secret, 14400, $identity);

        $videoGrant = new \Twilio\Jwt\Grants\VideoGrant();
        $videoGrant->setRoom($twilio_room_name);

        $token->addGrant($videoGrant);

        $user->access_token = $token->toJWT();
        $room->twilio_room_name = $twilio_room_name;       

    }
}
