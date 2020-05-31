<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Log;

use App\Events\NewRoomCreated;
use App\Events\NewCallCreated;
use App\Events\UserAddedToRoom;
use App\Events\CallDeclined;

class RoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function create(Request $request) 
    {
        $user = \Auth::user()->load('teams.rooms.users');

        if (!$user->teams->contains($request->team_id)) {
            abort(404);
        }

        $room_slug = Str::uuid();

        if (!isset($request->type) || $request->type != "call") {
            $room_slug = Str::slug($request->name, '-');

            $room = \App\Room::where('organization_id', $user->organization->id)->where('team_id', $request->team_id)->where('slug', $room_slug)->first();

            if ($room) {
                $room_slug = $room_slug . "-" . uniqid();
            }
        }

        if (isset($request->type) && $request->type == "call") {
            $participants = (array)$request->participants;
            $participants[] = $user->id;

            sort($participants);

            foreach ($user->teams as $team) {
                if ($team->id != $request->team_id) {
                    continue;
                }
                
                foreach ($team->rooms as $team_room) {
                    if ($team_room->type != "call") {
                        continue;
                    }

                    $team_room_participants = [];

                    foreach ($team_room->users as $team_room_user) {
                        $team_room_participants[] = $team_room_user->id;
                    }

                    sort($team_room_participants);

                    if ($participants == $team_room_participants) {
                        $room = $team_room;
                        unset($room->users);
                        $room->is_active = true;
                        //rotate their room secret from when we last called
                        $room->secret = Hash::make(Str::random(256));
                        $room->pin = Hash::make(Str::random(256));
                        $room->save();
                        break 2;
                    }
                }
            }

        }

        if (!isset($room)) {
            $room = new \App\Room();
            $room->team_id = $request->team_id;
            $room->organization_id = $user->organization->id;
            $room->slug = $room_slug;

            if (isset($request->type) && $request->type == "call") {
                $room->type = "call";
                $room->is_private = true;
                $room->video_enabled = true;
            } else {
                $room->name = $request->name;
                $room->is_private = $request->is_private;
                $room->video_enabled = $request->video_enabled;
            }

            $room->channel_id = Str::uuid();
            $room->secret = Hash::make(Str::random(256));
            $room->pin = Hash::make(Str::random(256));
            $room->is_active = true;

            if ($user->timezone != null) {
                if ($user->timezone == "America/New_York") {
                    $available_servers = \App\Server::where('is_active', 1)->where('location', 'us-east')->get();
                } else {
                    $available_servers = \App\Server::where('is_active', 1)->where('location', 'us-west')->get();
                }
            }
    
            if (!isset($available_servers) || count($available_servers) == 0) {
                $room->server_id = 1;
            }

            if (!isset($room->server_id)) {
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
            }


            $room->save();

            if ($room->is_private) {
                $user->rooms()->attach($room);
            }
    
            if ($room->type == "call") {
                foreach ($request->participants as $participant) {
                    $participant_user = \App\User::where('id', $participant)->first();
                    $participant_user->rooms()->attach($room);
                }
            }
            
        }

        $notification = new \stdClass;
        $notification->created_by = $user->id;
        $notification->room = $room;
        $notification->caller_name = $user->first_name;

        if ($room->type == "room" && $room->is_private == false) {
            //notify everyone else that a new room has been created
            broadcast(new NewRoomCreated($notification))->toOthers();
        } 

        if ($room->type == "call") {
            foreach ($request->participants as $participant) {
                $notification->callee_id = $participant;
                broadcast(new NewCallCreated($notification));
            }
        }

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

        if (!$user->teams->contains($room->team_id)) {
            abort(404);
        }

        if (!$room->users->contains($user)) {
            abort(404);
        }

        $team = \App\Team::where('id', $room->team_id)->first();

        if (!$team->users->contains($request->user_id)) {
            abort(403);
        }

        //make sure we don't attach more than once
        if (!$room->users->contains($request->user_id)) {
            $room->users()->attach($request->user_id);
        }

        $notification = new \stdClass;
        $notification->added_by = $user->id;
        $notification->room = $room;
        $notification->user = $request->user_id;

        //notify everyone else that a new user has been added to the room
        broadcast(new UserAddedToRoom($notification))->toOthers();

        return true;

    }

    public function answer_call(Request $request, $id) 
    {
        $user = \Auth::user()->load('teams.rooms');

        $room = \App\Room::where('id', $id)->with('users')->first();

        if (!$user->teams->contains($room->team_id)) {
            abort(404);
        }

        if (!$room->users->contains($user)) {
            abort(404);
        }

        $notification = new \stdClass;
        $notification->room = $room;

        foreach ($room->users as $room_user) {
            if ($room_user->id == $user->id) {
                continue;
            }
            $notification->recipient_id = $room_user->id;
            broadcast(new CallDeclined($notification));
        }

        return true;
    }

}
