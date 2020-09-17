<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\BillingUpdated;

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

        $environment = getenv('APP_ENV');

        if ($plan == "standard") {
            if ($environment == "production") {
                $plan_id = "price_1HRFHwFAJZnmFdvTvZczMHFq";
            } else {
                $plan_id = "price_1HRGkVFAJZnmFdvTy9dmTdsV";
            }
            $plan_name = "Standard";
            $plan_price = 5;
            $total = $plan_price * $plan_quantity;
        } else {
            if ($environment == "production") {
                $plan_id = "price_1HRFICFAJZnmFdvTMoSn8Qhl";
            } else {
                $plan_id = "price_1HRGkgFAJZnmFdvTjvsbv53Y";
            }
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
            'total' => $total,
            'discounted_total' => $total/2,
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
        $environment = getenv('APP_ENV');

        if ($request->plan == "price_1HRFHwFAJZnmFdvTvZczMHFq" || $request->plan == "price_1HRGkVFAJZnmFdvTy9dmTdsV") {
            $plan_name = "Blab Standard";
        }    
        
        if ($request->plan == "price_1HRFICFAJZnmFdvTMoSn8Qhl" || $request->plan == "price_1HRGkgFAJZnmFdvTjvsbv53Y") {
            $plan_name = "Blab Plus";
        }   

        if ($request->coupon != null && ($request->coupon == "HUNTHALFOFF" || $request->coupon == "ROO100")) {

            if ($request->coupon == "ROO100") {
                if ($environment == "production") {
                    $coupon = "I1RMqlTG";
                } else {
                    $coupon = "4XfKbe1k";
                }
            }

            if ($request->coupon == "HUNTHALFOFF") {
                $coupon = "2DXdr950";
            }

            if ($request->coupon == "50OFF2") {
                $coupon = "GISrCOsB";
            }

            $user->organization->newSubscription($plan_name, $request->plan)->trialDays(7)->quantity($plan_quantity)->withCoupon($coupon)->create($request->payment_method, [
                'email' => $user->email
            ], [
                'metadata' => ['organization_name' => $user->organization->name ]
            ]);
        } else {
            $user->organization->newSubscription($plan_name, $request->plan)->trialDays(7)->quantity($plan_quantity)->create($request->payment_method, [
                'email' => $user->email
            ], [
                'metadata' => ['organization_name' => $user->organization->name ]
            ]);
        }

        $notification = new \stdClass;
        $notification->organization_id = $user->organization->id;

        broadcast(new BillingUpdated($notification));

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
