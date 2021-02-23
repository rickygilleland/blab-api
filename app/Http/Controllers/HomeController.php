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

        $role = \App\Role::where('name', 'organization_admin')->first();

        $is_billing_admin = false;
        if ($user->roles()->exists($role)) {
            $is_billing_admin = true;
        }

        $billing = new \stdClass;
        $billing->plan = "Free";
        $billing->is_trial = false;

        if ($user->organization->onGenericTrial()) {
            $billing->plan = "Standard";
            $billing->is_trial = true;
            $billing->trial_ends_at = $user->organization->trial_ends_at->toFormattedDateString();
        }

        $is_billing_admin = false;

        return view('home', ['magic_login_link' => $magic_login_link, 'is_billing_admin' => $is_billing_admin, 'billing' => $billing, 'organization' => $user->organization]);
    }
}
