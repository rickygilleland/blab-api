<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class OrganizationController extends Controller
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

    public function show($organization_slug)
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

        return view('organization.index', ['user' => $user]);
    }


    public function api_show()
    {
        $user = \Auth::user()->load('teams.rooms', 'organization');
        
        $organization = [
            "id" => $user->organization->id,
            "name" => $user->organization->name,
            "slug" => $user->organization->slug,
            "teams" => $user->teams
        ];
        
        return $organization;
    }

    public function get_organization_users($id)
    {
        $user = \Auth::user()->load('organization');

        if ($user->organization->id != $id) {
            abort(404);
        }

        $organization_users = \App\Organization::find($id)->users;
    
        $users = [];

        foreach ($organization_users as $user) {
            $users[] = [
                'id' => $user->id, 
                'name' => $user->name,
                'avatar_url' => $user->avatar_url
            ];
        }

        //also fetch the invited users
        //all invited users should be created and attached to the organization with an accepted_invite -- existing users will have it true or otherwise false obviously

        return $users;
    }

    public function get_organization_teams($id)
    {
        $user = \Auth::user()->load('organization.teams.rooms');

        if ($user->organization->id != $id) {
            abort(404);
        }

        return $user->organization;
    }

    public function invite_users(Request $request, $id)
    {
        $user = \Auth::user()->load('organization.teams.rooms');

        if ($user->organization->id != $id) {
            abort(404);
        }

        print_r($request->emails);

        //invite the users
    }

}
