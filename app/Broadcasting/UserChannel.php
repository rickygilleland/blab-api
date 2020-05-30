<?php

namespace App\Broadcasting;

use App\User;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @return array|bool
     */
    public function join(User $user, $channelId)
    {
        if ($user->id == $channelId) {
            return [
                'id' => $user->id
            ];
        }
    }
}
