<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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

    public function organization()
    {
        $user = \Auth::user();

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

        return view('onboarding.invite', ['organization' => $user->organization]);
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

            $invite_email = new \SendGrid\Mail\Mail();
            $invite_email->setFrom("help@watercooler.work", "Water Cooler");
            $invite_email->addTo($email, "New Water Cooler User");

            $invite_email->addDynamicTemplateDatas([
                "subject" => $auth_user->first_name . " has invited you to join " . $auth_user->organization->name . " on Water Cooler",
                "organization_name" => $auth_user->organization->name,
                "inviter_name" => $auth_user->first_name,
                "invite_token" => base64_encode($invite->invite_code),
            ]);
        
            $invite_email->setTemplateId("d-ed053e9026d742eda4c66e5c5d6b2963");
            
            try {
                $response = $sg->send($invite_email);
            } catch (Exception $e) {
                //do something
            }
        }

        return redirect("/home")->width('status', 'Your teammates have been invited! Let them know to check their email.');
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

        $full_room_slug = $user->organization->slug.'/'.$default_team->slug.'/'.$room->slug;

        return redirect("/home");
    }

    public function download() {
        return view('onboarding.download');
    }
}
