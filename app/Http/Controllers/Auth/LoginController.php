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

        if ($user) {

            $magic_login_link = $user->id . "|" . $user->email . "|" . time();
            $magic_login_link = encrypt($magic_login_link);

            $full_login_link = "https://blab.to/magic/" . $magic_login_link;

            $email = new \stdClass;

            //make sure we don't send emails for the demo accounts
            $domain = explode("@", $user->email);
            if ($domain[1] == "acme.co") {
                $email->email = "ricky@blab.to";
            } else {
                $email->email = $user->email;
            }

            $email->name = $user->first_name;
            $email->data = [
                "name" => $user->first_name,
                "link" => $login_code,
                "subject" => "Blab Magic Login Link"
            ];
            $email->template_id = "d-1b1ca36d100f4e4db060c11e3044f92f";

            ProcessEmails::dispatch($email);

            return view('auth.code_sent', ['email' => $user->email]);
        }

        return view('auth.login', ['error' => 'We could not find an account under that address. Please try again.']);
    }

    public function webMagicLogin($magic_code) {
        if (!isset($magic_code) || $magic_code == null) {
            abort(404);
        }

        $decrypted_code = decrypt($magic_code);

        $decrypted_code = explode("|", $decrypted_code);

        if (count($decrypted_code) != 3) {
            abort(404);
        }

        $user = \App\User::where('id', $decrypted_code[0])->first();

        if (!$user) {
            abort(404);
        }

        if ($user->email != $decrypted_code[1]) {
            abort(404);
        }

        //codes expire after 1 hour
        if ((time() - 3600 > $decrypted_code[2])) {
            return view('auth.code_error', ['error' => 'Your magic login link has expired. Please close this window and request a new link.']);
        }

        if (!$user->is_active) {
            $user->is_active = true;
            $user->save();
        }

        \Auth::login($user);
        return redirect()->intended('home');
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
            $email->email = "ricky@blab.to";
        } else {
            $email->email = $user->email;
        }

        $email->name = $user->first_name;
        $email->data = [
            "name" => $user->first_name,
            "token" => $login_code,
            "subject" => "Your temporary Blab login code is ".$login_code
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
