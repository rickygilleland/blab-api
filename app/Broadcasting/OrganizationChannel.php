<?php

namespace App\Broadcasting;

use App\User;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Jobs\ProcessUsageEvent;

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

            $event = new \stdClass;
            $event->type = "joined_organization_presence_channel";
            $event->user_id = $user->id;
            $event->organization_id = $user->organization->id;

            ProcessUsageEvent::dispatch($event);

            return [
                'id' => $user->id
            ];
        }
    }
}
