<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
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
    public function index()
    {
        $user = \Auth::user()->load('roles', 'teams', 'organization');

        if (!$user->is_active) {
            return redirect('onboarding/confirm');
        }
        
        $teams = $user->teams;

        if (count($teams) == 0) {
            $teams = $user->organization->teams;
            $user->teams()->attach($teams[0]);
        }

        $default_team = $teams[0];

        foreach ($teams as $team) {
            if ($team->is_default) {
                $default_team = $team;
            }
        }

        if ($user->organization->name == null) {
            //prompt them to set up their organization (onboarding flow) -- used for all of the urls
            return redirect('onboarding/organization');
        }

        /*if ($team->name == null) {
            //prompt them to set up their team (onboarding flow)
            return redirect('onboarding/team');
        }*/

        $magic_login_link = $user->id . "|" . $user->email . "|" . time();

        $magic_login_link = encrypt($magic_login_link);

        return redirect('https://app.blab.to/magic/login/' . $magic_login_link);
        
    }
}
