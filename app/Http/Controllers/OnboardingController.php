<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Jobs\ProcessEmails;

class OnboardingController extends Controller
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

    public function confirm()
    {
        $user = \Auth::user();

        $invite = \App\Invite::where('email', $user->email)->first();
        if ($invite) {
            $user->is_active = true;
            $user->save();
            return redirect('/home');
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
            "subject" => "Your Water Cooler confirmation code is ".$login_code
        ];
        $email->template_id = "d-dd835e437d9f4aadaf1c9acb25e5f488";

        ProcessEmails::dispatch($email);

        return view('onboarding.confirm', ['email' => $user->email]);
    }

    public function register_confirm_code(Request $request)
    {
        $user = \Auth::user();

        $code_valid = false;
        foreach ($user->loginCodes as $code) {
            if (Hash::check($request->token, $code->code)) {
                $code_valid = $code;

                if ((strtotime($code->created_at) + 3600) < time()) {
                    return view('onboarding.confirm', ['error' => 'The code you entered is expired.']);
                }
            }
        }

        if ($code_valid === false) {
            return view('onboarding.confirm', ['email' => $request->email, 'error' => 'The code you entered was incorrect.']);
        }

        $code_valid->used = true;
        $code_valid->save();

        $organization = \App\Organization::where('id', $user->organization_id)->first();

        $user->is_active = true;
        $user->save();

        return redirect('home');

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

    public function organization()
    {
        $user = \Auth::user();

        if (!$user->is_active) {
            return redirect('onboarding/confirm');
        }

        if ($user->organization->name != null) {
            return redirect('onboarding/invite');
        }
        
        return view('onboarding.organization', ['organization' => $user->organization]);
    }

    public function organization_update(Request $request)
    {
        $user = \Auth::user();
        $organization = $user->organization;
        $organization->name = $request->name;
        $organization->slug = Str::slug($organization->name, '-');

        $slug_check = \App\Organization::where('slug', $organization->slug)->first();

        if ($slug_check) {
            $organization->slug = $organization->slug . "-" . uniqid();
        }

        $organization->save();

        //create their first room
        $teams = $user->teams;
        $default_team = $teams[0];

        $room = new \App\Room();
        $room->name = "Water Cooler";
        $room->team_id = $default_team->id;
        $room->organization_id = $user->organization->id;
        $room->slug = "water-cooler";
        $room->is_private = false;
        $room->video_enabled = false;
        $room->channel_id = Str::uuid();
        $room->secret = Hash::make(Str::random(256));
        $room->pin = Hash::make(Str::random(256));

        $available_servers = \App\Server::where('is_active', true)->get();

        if (!$available_servers) {
            $room->server_id = 1;
        }

        if (!isset($room->server_id)) {
            $rand = rand(0, (count($available_servers) - 1));
            $room->server_id = $available_servers[$rand]->id;
        }

        $room->save();

        $thread = new \App\Thread();
        $thread->slug = Str::random(12);
        $thread->type = "room";
        $thread->room_id = $room->id;
        $thread->save();

        $user->threads()->attach($thread);
        
        //skip the team setup for now until we have multi-team support
        //return redirect('onboarding/team');
        return redirect('onboarding/invite');
    }

    public function team()
    {
        $user = \Auth::user();
        $teams = $user->teams;
        $default_team = $teams[0];
        
        return view('onboarding.team', ['team' => $default_team, 'organization' => $user->organization]);
    }

    public function invite() 
    {
        $user = \Auth::user();

        $organization_invite = \App\Invite::where([
                ['organization_id', $user->organization->id],
                ['invite_type', 'organization_root']
            ])
            ->first();

        if (!$organization_invite) {
            $organization_invite = new \App\Invite();
            $organization_invite->email = "hello@watercooler.work";
            $organization_invite->name = "Created By System";
            $organization_invite->invited_by = 0;
            $organization_invite->invite_code = Hash::make(Str::random(256));
            $organization_invite->invite_sent = true;
            $organization_invite->invite_type = "organization_root";
            $organization_invite->organization_id = $user->organization->id;
            $organization_invite->save();
        }

        return view('onboarding.invite', ['organization' => $user->organization, 'organization_invite_code' => base64_encode($organization_invite->invite_code)]);
    }

    public function send_invite(Request $request)
    {
        $auth_user = \Auth::user();
        $emails = rtrim($request->emails);
        $emails = str_replace(array("\r", "\n"), '', $emails);

        $emails = explode(',', $emails);

        $sendgrid_key = env('SENDGRID_API_KEY');
        $sg = new \SendGrid($sendgrid_key);

        foreach ($emails as $email) {
            $email = trim($email);
            if (strlen($email) == 0) {
                continue;
            }

            $invite = new \App\Invite();
            $invite->email = $email;
            $invite->invite_code = Hash::make(Str::random(256));
            $invite->invited_by = $auth_user->id;
            $invite->organization_id = $auth_user->organization->id;
            $invite->team_id = $auth_user->organization->teams[0]->id;
            $invite->save();

            $invite_email = new \stdClass;

            //make sure we don't send emails for the demo accounts
            $domain = explode("@", $email);
            if ($domain[1] == "acme.co") {
                $invite_email->email = "ricky@watercooler.work";
            } else {
                $invite_email->email = $email;
            }

            $invite_email->name = "New Water Cooler User";
            $invite_email->data = [
                "subject" => $auth_user->first_name . " has invited you to join " . $auth_user->organization->name . " on Water Cooler",
                "organization_name" => $auth_user->organization->name,
                "inviter_name" => $auth_user->first_name,
                "invite_token" => base64_encode($invite->invite_code),
            ];
            $invite_email->template_id = "d-ed053e9026d742eda4c66e5c5d6b2963";

            ProcessEmails::dispatch($invite_email);
        }

        return redirect("/home")->with('status', 'Your teammates have been invited! Let them know to check their email.');
    }

    public function team_update(Request $request)
    {
        $user = \Auth::user();
        $teams = $user->teams;
        $default_team = $teams[0];

        $default_team->name = $request->name;
        $default_team->slug = Str::slug($default_team->name, '-');
        $default_team->save();

        //create their first watercooler room
        $room = new \App\Room();
        $room->name = "Water Cooler";
        $room->team_id = $default_team->id;
        $room->organization_id = $user->organization->id;
        $room->slug = Str::slug($room->name, '-');
        $room->channel_id = $user->organization->slug . "-" . $default_team->slug . "-" . $room->slug;
        $room->secret = Hash::make(Str::random(256));
        $room->is_private = false;
        $room->save();

        $thread = new \App\Thread();
        $thread->slug = Str::random(12);
        $thread->type = "room";
        $thread->room_id = $room->id;
        $thread->save();

        $user->threads()->attach($thread);

        $full_room_slug = $user->organization->slug.'/'.$default_team->slug.'/'.$room->slug;

        return redirect("/home");
    }

    public function download() {
        return view('onboarding.download');
    }
}
