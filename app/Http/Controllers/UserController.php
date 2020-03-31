<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
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

    public function show()
    {
        $user = \Auth::user();

        //fetch a twilio nts token and attach it to the user
        $client = new \Twilio\Rest\Client($this->sid, $this->token);

        $nts_token = $client->tokens->create();

        $user->nts_token = $nts_token;
        
        return $user;
    }
}
