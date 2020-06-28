<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SlackController extends Controller {

    public function start() {
        return redirect('https://slack.com/oauth/authorize?scope=identity.basic,identity.email,identity.team,identity.avatar&client_id=1000366406420.1003032710326');
    }

    public function callback()
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


}

