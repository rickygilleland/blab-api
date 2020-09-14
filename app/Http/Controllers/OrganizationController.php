<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Jobs\ProcessEmails;

class OrganizationController extends Controller
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

    public function show($organization_slug)
    {
        $user = \Auth::user()->load('teams.rooms', 'organization');
        
        //fetch the rooms within the organization and make sure this checks out
        $organization = \App\Organization::where('slug', $organization_slug)->with('teams')->first();

        if (!$organization) {
            abort(404);
        }

        if ($user->organization->id != $organization->id) {
            abort(404);
        }

        return view('organization.index', ['user' => $user]);
    }


    public function api_show()
    {
        $user = \Auth::user()->load('teams.rooms.thread', 'organization', 'rooms.thread', 'threads');

        /*if ($user->organization->trial_ends_at != '2021-06-10 23:59:59') {
            $user->organization->trial_ends_at = '2021-06-10 23:59:59';
            $user->organization->save();
        }*/

        $billing = new \stdClass;
        $billing->plan = "Free";
        $billing->is_trial = false;
        $billing->video_enabled = false;
        $billing->screen_sharing_enabled = false;

        if ($user->organization->onGenericTrial()) {
            $billing->plan = "Standard";
            $billing->is_trial = true;
            $billing->video_enabled = false;
            $billing->screen_sharing_enabled = false;
        }

        if ($user->organization->subscribed('Blab Standard')) {
            $billing->plan = "Standard";
            $billing->video_enabled = false;
            $billing->screen_sharing_enabled = false;
        }

        if ($user->organization->subscribed('Blab Plus')) {
            $billing->plan = "Plus";
            $billing->video_enabled = true;
            $billing->screen_sharing_enabled = true;
        }

        $teams = [];

        foreach ($user->teams as $team_key => $team) {
            $all_rooms = [];

            foreach ($team->rooms as $room_key => $room) {
                if ($room->is_private) {
                    foreach ($room->users as $room_user) {
                        if ($room_user->id == $user->id) {
                            $all_rooms[] = $room;
                            break;
                        }
                    }

                    continue;
                } 
                
                $all_rooms[] = $room;
            }

            $rooms = [];
            $calls = [];
            foreach ($all_rooms as $room) {
                if ($room->type == "room") {

                    if ($room->thread == null) {
                        $thread = new \App\Thread();
                        $thread->slug = Str::random(12);
                        $thread->type = "room";
                        $thread->room_id = $room->id;
                        $thread->save();

                        $user->threads()->attach($thread);

                        $room->thread = $thread;

                        $room = \App\Room::where('id', $room->id)->with(['users', 'thread'])->first();
                        $rooms[] = $room;
                        continue;
                    }

                    if (!$user->threads->contains($room->thread->id)) {
                        $user->threads()->attach($room->thread->id);
                    }

                    $rooms[] = $room;
                    continue;
                }

                $calls[] = $room;
            }

            $newTeam = new \stdClass;
            $newTeam->id = $team->id;
            $newTeam->name = $team->name;
            $newTeam->is_default = $team->is_default;
            $newTeam->avatar_url = $team->avatar_url;
            $newTeam->organization_id = $team->organization_id;
            $newTeam->rooms = $rooms;
            $newTeam->calls = $calls;

            $teams[] = $newTeam;
        }
        
        $organization = [
            "id" => $user->organization->id,
            "name" => $user->organization->name,
            "slug" => $user->organization->slug,
            "billing" => $billing,
            "teams" => $teams
        ];
        
        return $organization;
    }

    public function get_organization_users($id)
    {
        $user = \Auth::user()->load('organization');

        if ($user->organization->id != $id) {
            abort(404);
        }

        $organization_users = \App\Organization::find($id)->users;
    
        $users = [];

        foreach ($organization_users as $user) {
            $users[] = [
                'id' => $user->id, 
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'avatar_url' => $user->avatar_url,
                'timezone' => $user->timezone
            ];
        }

        //also fetch the invited users
        //all invited users should be created and attached to the organization with an accepted_invite -- existing users will have it true or otherwise false obviously

        return $users;
    }

    public function get_organization_teams($id)
    {
        $user = \Auth::user()->load('organization.teams.rooms');

        if ($user->organization->id != $id) {
            abort(404);
        }

        return $user->organization;
    }

    public function invite_users(Request $request, $id)
    {
        $auth_user = \Auth::user()->load('organization.teams.rooms');

        if ($auth_user->organization->id != $id) {
            abort(404);
        }

        $emails = trim($request->emails);

        $emails = explode(',', $emails);

        $sendgrid_key = env('SENDGRID_API_KEY');
        $sg = new \SendGrid($sendgrid_key);

        foreach ($emails as $email) {
            $invite = new \App\Invite();
            $invite->email = $email;
            $invite->invite_code = Hash::make(Str::random(256));
            $invite->invited_by = $auth_user->id;
            $invite->organization_id = $id;
            $invite->team_id = $auth_user->organization->teams[0]->id;
            $invite->save();

            $invite_email = new \stdClass;

            //make sure we don't send emails for the demo accounts
            $domain = explode("@", $email);
            if ($domain[1] == "acme.co") {
                $invite_email->email = "ricky@blab.to";
            } else {
                $invite_email->email = $email;
            }

            $invite_email->name = "New Blab User";
            $invite_email->data = [
                "subject" => $auth_user->first_name . " has invited you to join " . $auth_user->organization->name . " on Blab",
                "organization_name" => $auth_user->organization->name,
                "inviter_name" => $auth_user->first_name,
                "invite_token" => base64_encode($invite->invite_code),
            ];
            $invite_email->template_id = "d-ed053e9026d742eda4c66e5c5d6b2963";

            ProcessEmails::dispatch($invite_email);

        }


        return true;
    }

}
