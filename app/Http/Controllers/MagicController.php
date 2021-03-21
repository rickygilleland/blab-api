<?php

namespace App\Http\Controllers;

class MagicController extends Controller
{
    public function webAppMagicLogin() {
        $user = \Auth::user();

        $magic_login_link = $this->getMagicCode($user->id, $user->email);
        $full_login_link = "blab::/magic/login/" . $magic_login_link;

        return redirect($full_login_link);
    }

    private function getMagicCode($userId, $email) {
        $magic_login_link = $userId . "|" . $email . "|" . time();
        $magic_login_link = encrypt($magic_login_link);

        return $magic_login_link;
    }
}
