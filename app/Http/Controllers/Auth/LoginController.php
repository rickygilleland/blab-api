<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Jobs\ProcessEmails;

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

    public function login(Request $request) {
        //make sure we have a user
        $user = \App\User::where('email', $request->email)->first();

        if ($user && isset($request->token)) {
            foreach ($user->loginCodes as $code) {
                if (Hash::check($request->token, $code->code)) {
                    if ($code->user_id != $user->id || $code->used 
                        || (strtotime($code->created_at) + 3600) < time()) {
                        
                        return view('auth.code_sent', ['email' => $user->email, 'error' => 'The code you entered was incorrect.']);

                    }
                    
                    $code->used = true;
                    $code->save();

                    $organization = \App\Organization::where('id', $user->organization_id)->first();

                    if (!$user->is_active) {
                        $user->is_active = true;
                        $user->save();
                    }
    
                    \Auth::login($user);
                    return redirect()->intended('home');
                }
            } 

            return view('auth.code_sent', ['email' => $user->email, 'error' => 'The code you entered was incorrect.']);
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

            $email = new \stdClass;

            //make sure we don't send emails for the demo accounts
            $domain = explode("@", $user->email);
            if ($domain[1] == "acme.co") {
                $email->email = "ricky@watercooler.work";
            } else {
                $email->email = $user->email;
            }

            $email->name = $user->first_name;
            $email->data = [
                "name" => $user->first_name,
                "token" => $login_code,
                "subject" => "Your temporary Water Cooler login code is ".$login_code
            ];
            $email->template_id = "d-dd835e437d9f4aadaf1c9acb25e5f488";

            ProcessEmails::dispatch($email);

            return view('auth.code_sent', ['email' => $user->email]);
        }

        return view('auth.login', ['error' => 'We could not find an account under that address. Please try again.']);
    }

    public function apiRequestLoginCode(Request $request)
    {
        $user = \App\User::where('email', $request->email)->first(); 

        if (!$user) {
            abort(404);
        }
        
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

        $email = new \stdClass;

        //make sure we don't send emails for the demo accounts
        $domain = explode("@", $user->email);
        if ($domain[1] == "acme.co") {
            $email->email = "ricky@watercooler.work";
        } else {
            $email->email = $user->email;
        }

        $email->name = $user->first_name;
        $email->data = [
            "name" => $user->first_name,
            "token" => $login_code,
            "subject" => "Your temporary Water Cooler login code is ".$login_code
        ];
        $email->template_id = "d-dd835e437d9f4aadaf1c9acb25e5f488";

        ProcessEmails::dispatch($email);

        return true;
        
    }

    public function apiMagicAuth(Request $request) 
    {
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

        //codes expire after 1 hour
        if ((time() - 3600 > $decrypted_code[2])) {
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

        $max = $length/2;
        for ($i = 1; $i <= $max; $i++) {
            $string .= $consonants[rand(0,19)];
            $string .= $vowels[rand(0,4)];
        }

        return $string;
    }
}
