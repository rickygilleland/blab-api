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
            $random_theme = $themes[$random_theme];

            $user->avatar_url = $avi_base . md5($user->name) . "?theme=" . $random_theme . "&numcolors=4&size=880&fmt=svg";
            $user->save();
        }

        return $user;
    }

    public function update(Request $request, $id)
    {
        $user = \Auth::user();

        if ($user->id != $id) {
            abort(404);
        }

        if (isset($request->timezone)) {
            $user->timezone = $request->timezone;
        }

        if (isset($request->current_room_id)) {
            $user->current_room_id = $request->current_room_id;
        }

        $user->save();

        return $user;
    }
}
