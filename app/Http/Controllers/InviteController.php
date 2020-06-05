<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Jobs\ProcessEmails;

class InviteController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($code)
    {
        $invite = \App\Invite::where('invite_code', base64_decode($code))->first();

        if (!$invite) {
            abort(404);
        }

        if ($invite->invite_accepted) {
            return view('invite.accepted');
        } 

        if ($invite->organization_id == null) {
            //this was an internal, new account invite, show them the new account sign up page
            return view('invite.new_account', ['invite_code' => base64_decode($code)]);
        }

        $organization = \App\Organization::where('id', $invite->organization_id)->first();

        return view('invite.existing', ['organization' => $organization, 'invite_code' => base64_decode($code)]);
    }

    public function admin_create_invite_form() {

        if (!\Auth::check()) {
            abort(404);
        }

        $user = \Auth::user()->load('roles');

        if (!$user) {
            abort(404);
        }

        $has_admin = false;
        foreach ($user->roles as $role) {
            if ($role->name == "system_admin") {
                $has_admin = true;
            }
        }

        if (!$has_admin) {
            abort(404);
        }

        return view('invite.admin_create');
    }

    public function request_invite() {

        //check how many users we've accepted today, if less than 10 let them sign up immediately
        $users = User::where('created_at', '>', Carbon::now()->subHours(24))->count();

        if ($users > 100) {
            return view('invite.request');
        }

        $invite = new \App\Invite();
        $invite->email = "hello@watercooler.work";
        $invite->name = "Created By System";
        $invite->invited_by = 0;
        $invite->invite_code = Hash::make(Str::random(256));
        $invite->invite_sent = true;
        $invite->save();

        return redirect('/invite/'.base64_encode($invite->invite_code));
    }

    public function submit_invite_request(Request $request) {

        //check if we already have an invite for them
        $invite = \App\Invite::where([
            ['email', $request->email],
            ['organization_id', null]
        ])
        ->first();

        if ($invite) {
            return view('invite.request', ['success' => true ]);
        }

        $invite = new \App\Invite();
        $invite->email = $request->email;
        $invite->name = $request->name;
        $invite->invited_by = 0;
        $invite->invite_code = Hash::make(Str::random(256));
        $invite->invite_sent = false;
        $invite->save();

        return view('invite.request', ['success' => true ]);
    }

    public function admin_create_invite(Request $request) {

        if (!\Auth::check()) {
            abort(404);
        }

        $user = \Auth::user()->load('roles');

        if (!$user) {
            abort(404);
        }

        $has_admin = false;
        foreach ($user->roles as $role) {
            if ($role->name == "system_admin") {
                $has_admin = true;
            }
        }

        if (!$has_admin) {
            abort(404);
        }

        if ($request->name == null || $request->name == '') {
            return view('invite.admin_create', ['success' => false ]);
        }
 
        $invite = new \App\Invite();
        $invite->email = $request->email;
        $invite->invited_by = 0;
        $invite->invite_code = Hash::make(Str::random(256));
        $invite->save();

        $sendgrid_key = env('SENDGRID_API_KEY');
        $sg = new \SendGrid($sendgrid_key);

        $invite_email = new \SendGrid\Mail\Mail();
        $invite_email->setFrom("help@watercooler.work", "Water Cooler");
        $invite_email->addTo($request->email, $request->name);

        $invite_email->addDynamicTemplateDatas([
            "subject" => $request->name . ": You are Invited to Try Water Cooler",
            "first_name" => $request->name,
            "invite_token" => base64_encode($invite->invite_code),
        ]);
    
        $invite_email->setTemplateId("d-4af02e391aff4fbba88409c2be1ccef5");
        
        try {
            $response = $sg->send($invite_email);
        } catch (Exception $e) {
            //do something
        }

        return view('invite.admin_create', ['success' => true, "email" => $request->email, "invite_link" => "https://watercooler.work/invite/".base64_encode($invite->invite_code)]);
    }
}
