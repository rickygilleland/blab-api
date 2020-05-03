<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function admin_create_invite(Request $request) {
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

        $invite = new \App\Invite();
        $invite->email = $request->email;
        $invite->invited_by = 0;
        $invite->invite_code = Hash::make(Str::random(256));
        $invite->save();

        return view('invite.admin_create', ['success' => true, "email" => $request->email, "invite_link" => "https://watercooler.work/invite/".base64_encode($invite->invite_code)]);
    }
}
