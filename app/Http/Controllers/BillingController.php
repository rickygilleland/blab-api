<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
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
        $user = \Auth::user()->load('roles', 'organization');

        $role = \App\Role::where('name', 'organization_admin')->first();
        
        if ($user->roles()->exists($role) == false) {
            return view('billing.unauthorized');
        }

        $billing = new \stdClass;
        $billing->plan = "Free";
        $billing->is_trial = false;

        if ($user->organization->onGenericTrial()) {
            $billing->plan = "Standard";
            $billing->is_trial = true;
            $billing->trial_ends_at = $user->organization->trial_ends_at->toFormattedDateString();
        }

        return view('billing.index', ['organization' => $user->organization, 'billing' => $billing]);
    }

    public function upgrade_form()
    {
        $user = \Auth::user()->load('roles', 'organization');

        $role = \App\Role::where('name', 'organization_admin')->first();
        
        if ($user->roles()->exists($role) == false) {
            return view('billing.unauthorized');
        }

        $billing = new \stdClass;
        $billing->plan = "Free";
        $billing->is_trial = false;

        if ($user->organization->onGenericTrial()) {
            $billing->plan = "Standard";
            $billing->is_trial = true;
            $billing->trial_ends_at = $user->organization->trial_ends_at->toFormattedDateString();
        }

        return view('billing.upgrade', ['organization' => $user->organization, 'billing' => $billing]);
    }

    public function upgrade(Request $request)
    {

    }
}
