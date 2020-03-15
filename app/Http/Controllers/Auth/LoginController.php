<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function handleSlackCallback()
    {

        $slack_user = Socialite::driver('slack')->stateless()->user();

        //find or create the user
        $user = \App\User::where('email', $slack_user->email)->first();

        if (!$user) {

            //find or create the organization
            $organization = \App\Organization::where('provider_id', $slack_user->organization_id)->first();

            if (!$organization) {
                $organization = \App\Organization::where('email_domain', substr(strrchr($slack_user->email, "@"), 1))->first();
            }

            if (!$organization) {
                $organization = new \App\Organization();
                $organization->provider_id = $slack_user->organization_id;
                $organization->email_domain = substr(strrchr($slack_user->email, "@"), 1);
                $organization->save();
            }

            //find or create the team
            $team = \App\Team::where('provider_id', $slack_user->user['team']['id'])->first();

            if (!$team) {
                $team = new \App\Team();
                $team->provider_id = $slack_user->user['team']['id'];
                $team->avatar_url = $slack_user->user['team']['image_230'];
                $team->organization_id = $organization->id;
                $team->save();
            }

            $user = new \App\User();
            $user->email = $slack_user->email;
            $user->avatar_url = $slack_user->avatar;
            $user->organization_id = $organization->id;
            $user->password = Hash::make(Str::random(60));
            $user->name = $slack_user->name;

            $user->save();
            $user->teams()->attach($team);
        }
 
        $user->provider = 'slack';
        $user->provider_id = $slack_user->id;

        $user->save();

        if ($user) {
            \Auth::login($user);
            return redirect()->intended('home');
        }

    }
}
