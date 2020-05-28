<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;

use App\User;
use App\Jobs\ProcessEmails;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'invite_code' => ['required', 'exists:invites,invite_code'],
            'avatar' => ['nullable', 'image']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $invite = \App\Invite::where('invite_code', $data['invite_code'])->first();

        $request = request();

        if ($invite->organization_id == null) {
            $organization = new \App\Organization();
            $email_domain = substr(strrchr($data['email'], "@"), 1);
    
            if ($email_domain != "gmail.com" && $email_domain != "yahoo.com" && $email_domain != "hotmail.com" && $email_domain != "live.com") {
                $organization->email_domain = $email_domain;
            }
    
            $organization->slug = md5($data['email'] . uniqid()) . uniqid();
            $organization->trial_ends_at = now()->addDays(7);
            $organization->save();

            $team = new \App\Team();
            $team->organization_id = $organization->id;
            $team->is_default = true;
            $team->name = "default";
            $team->slug = md5($data['email'] . uniqid()) . uniqid();
            $team->save();
        }

        $user = new \App\User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->password = Hash::make(Str::random(256));
        $user->organization_id = $invite->organization_id != null ? $invite->organization_id : $organization->id;
        $user->streamer_key = Hash::make(Str::random(256));
        $user->last_login_at = Carbon::now();
        $user->save();

        $role = \App\Role::where('name', 'organization_admin')->first();
        $user->roles()->attach($role, ['organization_id' => $organization->id]);

        if ($request->hasFile('avatar')) {
            try {
                $avatar_url = Storage::disk('spaces')->putFile('avatars', $request->file('avatar'), 'public');
                $avatar_url = "https://watercooler-uploads.sfo2.cdn.digitaloceanspaces.com/" . $avatar_url;
            } catch (\Exception $e) {
                //do something
            }
        } else {
            //generate a random one
            $avi_base = env('AVI_SERVICE_URL');

            $themes = [
                "frogideas",
                "sugarsweets",
                "heatwave",
                "daisygarden",
                "seascape",
                "summerwarmth",
                "bythepool",
                "duskfalling",
                "berrypie"
            ];

            $random_theme = array_rand($themes, 1);
            $random_theme = $themes[$random_theme];

            $avatar_url = $avi_base . md5($user->name) . "?theme=" . $random_theme . "&numcolors=4&size=880&fmt=svg";
        }

        $user->avatar_url = $avatar_url;
        $user->save();

        if (!isset($team)) {
            $team = \App\Team::where('id', $invite->team_id)->first();
        }

        $user->teams()->attach($team);

        $invite->invite_accepted = true;
        $invite->save();

        $email = new \stdClass;
        $email->type = "text_only";
        $email->email = "ricky@watercooler.work";
        $email->name = "Ricky Gilleland";
        $email->subject = "New User Registered";
        $email->content = "A new user signed up for Water Cooler. ID: ".$user->id;

        ProcessEmails::dispatch($email);

        return $user;
    }
}
