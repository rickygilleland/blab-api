<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'invite_code' => ['required', 'exists:invites,invite_code']
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
        $organization = new \App\Organization();
        $email_domain = substr(strrchr($data['email'], "@"), 1);

        if ($email_domain != "gmail.com" && $email_domain != "yahoo.com" && $email_domain != "hotmail.com" && $email_domain != "live.com") {
            $organization->email_domain = $email_domain;
        }

        $organization->slug = md5($data['email'] . uniqid()) . uniqid();
        $organization->save();

        $team = new \App\Team();
        $team->organization_id = $organization->id;
        $team->is_default = true;
        $team->slug = md5($data['email'] . uniqid()) . uniqid();
        $team->save();

        $user = new \App\User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->organization_id = $organization->id;
        $user->streamer_key = Hash::make(Str::random(256));
        $user->save();

        $user->teams()->attach($team);

        return $user;
    }
}
