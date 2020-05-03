<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
                //temporarily set the slug to the provider id
                $organization->slug = $organization->provider_id;
                //create a generic trial sub
                $organization->trial_ends_at = now()->addDays(7);
                $organization->save();
            }

            //find or create the team
            $team = \App\Team::where('provider_id', $slack_user->user['team']['id'])->first();

            if (!$team) {
                $team = new \App\Team();
                $team->provider_id = $slack_user->user['team']['id'];
                $team->avatar_url = $slack_user->user['team']['image_230'];
                $team->organization_id = $organization->id;
                $team->is_default = true;
                //temporarily set the slug to the provider id
                $team->slug = $team->provider_id;
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

        if ($user->avatar_url != $slack_user->avatar) {
            $user->avatar_url = $slack_user->avatar;
        }
 
        $user->provider = 'slack';
        $user->provider_id = $slack_user->id;

        $user->save();

        if ($user) {
            \Auth::login($user);

            return redirect()->intended('home');
        }

    }

    public function apiHandleSlackCallback()
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
                //temporarily set the slug to the provider id
                $organization->slug = $organization->provider_id;
                //create a generic trial sub
                $organization->trial_ends_at = now()->addDays(7);
                $organization->save();
            }

            //find or create the team
            $team = \App\Team::where('provider_id', $slack_user->user['team']['id'])->first();

            if (!$team) {
                $team = new \App\Team();
                $team->provider_id = $slack_user->user['team']['id'];
                $team->avatar_url = $slack_user->user['team']['image_230'];
                $team->organization_id = $organization->id;
                $team->is_default = true;
                //temporarily set the slug to the provider id
                $team->slug = $team->provider_id;
                $team->save();
            }

            $user = new \App\User();
            $user->email = $slack_user->email;
            $user->avatar_url = $slack_user->avatar;
            $user->organization_id = $organization->id;
            $user->password = Hash::make(Str::random(256));
            $user->streamer_key = Hash::make(Str::random(256));
            $user->name = $slack_user->name;

            $user->save();
            $user->teams()->attach($team);
        }

        if ($user->avatar_url != $slack_user->avatar) {
            $user->avatar_url = $slack_user->avatar;
        }
 
        $user->provider = 'slack';
        $user->provider_id = $slack_user->id;

        $user->save();

        //return the token
        $token = $user->createToken('Token Name')->accessToken;

        return ['access_token' => $token];

    }

    public function login(Request $request) {
        //make sure we have a user
        $user = \App\User::where('email', $request->email)->first();

        if ($user && isset($request->token)) {
            $code = \App\LoginCode::where('code', Hash::make($request->token))->first();

            if (!$code || $code->user_id != $user->id || $code->used 
                || (strtotime($code->created_at) + 3600) < time()) {
                return view('auth.code_sent', ['email' => $user->email, 'error' => 'The code you entered was incorrect.']);
            }

            $code->used = true;
            $code->save();

            \Auth::login($user);
            return redirect()->intended('home');
        }

        if ($user) {

            //generate a new code
            $code = new \App\LoginCode();
            $code->user_id = $user->id;

            $login_code = '';

            for ($i=0; $i<3; $i++) {
                $login_code .= $this->generateHumanReadableString(4);

                if ($i != 2) {
                    $login_code .= "-";
                } 
            }

            $code->code = Hash::make($login_code);
            $code->save();

            $sendgrid_key = env('SENDGRID_API_KEY');
            $sg = new \SendGrid($sendgrid_key);

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("help@watercooler.work", "Water Cooler");

            //make sure we don't send emails for the demo accounts
            $domain = explode("@", $user->email);
            if ($domain[1] == "acme.co") {
                $email->addTo("ricky@watercooler.work");
            } else {
                $email->addTo($user->email);
            }

            $email->addDynamicTemplateDatas([
                "name" => $user->first_name,
                "token" => $login_code,
                "subject" => "Your temporary Water Cooler login code is ".$login_code
            ]);
        
            $email->setTemplateId("d-dd835e437d9f4aadaf1c9acb25e5f488");
            
            try {
                $response = $sg->send($email);
            } catch (Exception $e) {
                //do something
            }

            return view('auth.code_sent', ['email' => $user->email]);
        }

        return view('auth.login', ['error' => 'We could not find an account under that address. Please try again.']);
    }

    function apiMagicAuth(Request $request) {
        if (!isset($request->code) || $request->code == null) {
            abort(500);
        }

        $decrypted_code = decrypt($request->code);

        $decrypted_code = explode("|", $decrypted_code);

        if (count($decrypted_code) != 3) {
            abort(500);
        }

        $user = \App\User::where('id', $decrypted_code[0])->first();

        if (!$user) {
            abort(500);
        }

        if ($user->email != $decrypted_code[1]) {
            abort(500);
        }

        //everything is good, login
        $token = $user->createToken('Token created by Magic Link')->accessToken;

        return ['access_token' => $token];
        
    }

    public function generateHumanReadableString($length) {
        $string     = '';
        $vowels     = array("a","e","i","o","u");  
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );  

        // Seed it
        srand((double) microtime() * 1000000);

        $max = $length/2;
        for ($i = 1; $i <= $max; $i++) {
            $string .= $consonants[rand(0,19)];
            $string .= $vowels[rand(0,4)];
        }

        return $string;
    }
}
