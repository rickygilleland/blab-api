<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = \Auth::user();

        $magic_login_link = $user->id . "|" . $user->email . "|" . time();

        $magic_login_link = encrypt($magic_login_link);
        
        return view('home', ['magic_login_link' => $magic_login_link]);
    }
}
