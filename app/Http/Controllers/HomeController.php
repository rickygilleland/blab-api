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
        $user = \Auth::user()->load('teams', 'organization');
        
        $teams = $user->teams;
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

        if ($team->name == null) {
            //prompt them to set up their team (onboarding flow)
            return redirect('onboarding/team');
        }

        if (count($user->teams) == 1) {
            return redirect('o/'.$user->organization->slug.'/'.$default_team->slug);
        } 
        
        return view('home');
    }
}
