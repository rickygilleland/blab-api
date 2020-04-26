<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use TwilioRestClient;
use TwilioJwtAccessToken;
use TwilioJwtGrantsVideoGrant;

class OnboardingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $sid;
	protected $token;
	protected $key;
	protected $secret;
	
	public function __construct()
	{
        $this->middleware('auth');
        
        $this->sid = config('services.twilio.sid');
		$this->token = config('services.twilio.token');
		$this->key = config('services.twilio.key');
        $this->secret = config('services.twilio.secret');
    }

    public function organization()
    {
        $user = \Auth::user();

        if ($user->organization->name != null) {
            return redirect('onboarding/team');
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
        
        return redirect('onboarding/team');
    }

    public function team()
    {
        $user = \Auth::user();
        $teams = $user->teams;
        $default_team = $teams[0];
        
        return view('onboarding.team', ['team' => $default_team, 'organization' => $user->organization]);
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
        $room->is_public = false;
        $room->save();

        $full_room_slug = $user->organization->slug.'/'.$default_team->slug.'/'.$room->slug;

        return redirect("/onboarding/download");
    }

    public function download() {
        return view('onboarding.download');
    }
}
