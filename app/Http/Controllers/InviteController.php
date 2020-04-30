<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        if ($invite->organization_id == null) {
            //this was an internal, new account invite, show them the new account sign up page
            return view('invite.new_account');
        }

        $organization = \App\Organization::where('id', $invite->organization_id)->first();

        return view('invite.existing', ['organization' => $organization]);
    }
}
