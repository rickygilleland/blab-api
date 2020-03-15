<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($organization_slug, $team_slug, $room_slug)
    {
        //fetch the rooms within the organization and make sure this checks out
        $organization = \App\Organization::where('slug', $organization_slug)->with('teams.rooms')->first();

        if (!$organization) {
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
        
        return view('room.index', ['organization' => $organization, 'team' => $team, 'room' => $room]);
    }
}
