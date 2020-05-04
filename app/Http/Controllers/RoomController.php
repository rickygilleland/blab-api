<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use TwilioRestClient;
use TwilioJwtAccessToken;
use TwilioJwtGrantsVideoGrant;

use App\Events\NewRoomCreated;

class RoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

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
        $room->secret = Hash::make(Str::random(256));

        $available_servers = \App\Server::where('is_active', true)->get();

        if (!$available_servers) {
            abort(503);
        }

        //TODO: get utilization stats from the server to make sure it isn't overloaded
        $rand = rand(0, (count($available_servers) - 1));

        $room->server_id = $available_servers[$rand]->id;
        $room->save();

        $room->secret .= "_" . $room->id;
        $room->save();

        //notify everyone else that a new room has been created
        broadcast(new NewRoomCreated($user->organization))->toOthers();

        return $room;
    }

}
