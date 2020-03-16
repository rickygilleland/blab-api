<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($organization_slug, $team_slug)
    {
        $user = \Auth::user()->load('teams.rooms', 'organization');
        
        //fetch the rooms within the organization and make sure this checks out
        $organization = \App\Organization::where('slug', $organization_slug)->with('teams')->first();

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

        return view('team.index', ['user' => $user, 'team' => $current_team]);
    }
}
