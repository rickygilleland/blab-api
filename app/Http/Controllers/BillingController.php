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

    public function show_upgrade_form($plan)
    {
        $user = \Auth::user()->load('roles', 'organization.users');

        $role = \App\Role::where('name', 'organization_admin')->first();
        
        if ($user->roles()->exists($role) == false) {
            return view('billing.unauthorized');
        }

        $billing = new \stdClass;
        $billing->plan = "Free";
        $billing->is_trial = false;

        $plan_quantity = count($user->organization->users);

        if ($plan == "standard") {
            $plan_id = "price_1HRFHwFAJZnmFdvTvZczMHFq";
            $plan_name = "Standard";
            $plan_price = 5;
            $total = $plan_price * $plan_quantity;
        } else {
            $plan_id = "price_1HRFICFAJZnmFdvTMoSn8Qhl";
            $plan_name = "Plus";
            $plan_price = 10;
            $total = $plan_price * $plan_quantity;
        }
        
        return view('billing.upgrade', [
            'intent' => $user->organization->createSetupIntent(),
            'organization' => $user->organization, 
            'billing' => $billing,
            'plan_id' => $plan_id,
            'plan_name' => $plan_name,
            'plan_price' => $plan_price,
            'plan_quantity' => $plan_quantity,
            'total' => $total
        ]);
    }

    public function upgrade(Request $request)
    {
        $user = \Auth::user()->load('roles', 'organization');

        $role = \App\Role::where('name', 'organization_admin')->first();
        
        if ($user->roles()->exists($role) == false) {
            abort(500);
        }

        $plan_quantity = count($user->organization->users);

        if ($request->coupon != null && $request->coupon == "HUNTHALFOFF") {
            $user->organization->newSubscription('Blab', $request->plan_id)->quantity($plan_quantity)->withCoupon($request->coupon)->create($request->payment_method, [
                'email' => $user->email
            ], [
                'metadata' => ['organization_name' => $user->organization->name ]
            ]);
        } else {
            $user->organization->newSubscription('Blab', $request->plan_id)->quantity($plan_quantity)->create($request->payment_method, [
                'email' => $user->email
            ], [
                'metadata' => ['organization_name' => $user->organization->name ]
            ]);
        }

        return true;

    }

    public function redirectBillingPortal(Request $request) {

        $user = \Auth::user()->load('roles', 'organization');

        $role = \App\Role::where('name', 'organization_admin')->first();
        
        if ($user->roles()->exists($role) == false) {
            return view('billing.unauthorized');
        }

        try {
            return $user->organization->redirectToBillingPortal();
        } catch (\Exception $e) {
            return redirect("/billing");
        }

    }
}
