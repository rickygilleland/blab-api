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

        if ($user->avatar_url == null) {
            //generate a random one
            $avi_base = env('AVI_SERVICE_URL');

            $themes = [
                "frogideas",
                "sugarsweets",
                "heatwave",
                "daisygarden",
                "seascape",
                "summerwarmth",
                "bythepool",
                "duskfalling",
                "berrypie"
            ];

            $random_theme = array_rand($themes, 1);
            $random_theme = $random_theme[0];

            $user->avatar_url = $avi_base . md5($user->name) . "?theme=" . $random_theme . "&numcolors=4&size=880&fmt=svg";
            $user->save();
        }
        
        return $user;
    }
}
