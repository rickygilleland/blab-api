<?php

namespace App\Broadcasting;

use App\User;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class OrganizationChannel
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
        if ($user->organization->id == $channelId) {
            return [
                'id' => $user->id
            ];
        }
    }
}
