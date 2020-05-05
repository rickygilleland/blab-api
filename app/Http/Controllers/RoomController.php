<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use TwilioRestClient;
use TwilioJwtAccessToken;
use TwilioJwtGrantsVideoGrant;

use App\Events\NewRoomCreated;
use App\Events\UserAddedToRoom;

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

        if ($room->is_private) {
            $user->rooms()->attach($room);
        }

        $notification = new \stdClass;
        $notification->created_by = $user->id;
        $notification->room = $room;

        //notify everyone else that a new room has been created
        broadcast(new NewRoomCreated($notification))->toOthers();

        return $room;
    }

    public function get_users($id)
    {

        $user = \Auth::user()->load('teams');

        $room = \App\Room::where('id', $id)->with('users')->first();
        
        $team_found = false;
        foreach ($user->teams as $team) {
            if ($team->id == $room->team_id) {
                $team_found = true;
            }
        }

        if (!$team_found) {
            abort(404);
        }

        if ($room->is_private) {
            $user_found = false;
            foreach ($room->users as $room_user) {
                if ($room_user->id == $user->id) {
                    $user_found = true;
                    break;
                }
            }

            if (!$user_found) {
                abort(404);
            }
        }

        return $room->users;

    }

    public function invite_user(Request $request, $id)
    {
        $user = \Auth::user()->load('teams');

        $room = \App\Room::where('id', $id)->with('users')->first();
        
        $team_found = false;
        foreach ($user->teams as $team) {
            if ($team->id == $room->team_id) {
                $team_found = true;
            }
        }

        if (!$team_found) {
            abort(404);
        }

        if ($room->is_private) {
            $user_found = false;
            foreach ($room->users as $room_user) {
                if ($room_user->id == $user->id) {
                    $user_found = true;
                    break;
                }
            }

            if (!$user_found) {
                abort(404);
            }
        }

        $user_to_add = \App\User::where('id', $request->user_id)->first();

        if (!$user_to_add) {
            abort(404);
        }

        $room->users()->attach($user_to_add);

        $notification = new \stdClass;
        $notification->added_by = $user->id;
        $notification->room = $room;
        $notification->user = $user_to_add;

        //notify everyone else that a new user has been added to the room
        broadcast(new UserAddedToRoom($notification))->toOthers();

        return true;

    }

}
