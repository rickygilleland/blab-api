<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Broadcast;

use App\Broadcasting\OrganizationChannel;
use App\Broadcasting\UserChannel;
use App\Broadcasting\RoomChannel;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('organization.{channelId}', OrganizationChannel::class);
Broadcast::channel('user.{channelId}', UserChannel::class);
Broadcast::channel('room.{channelId}', RoomChannel::class);

/*Broadcast::channel('peers.{channelId}', function ($user, $channelId) {
    foreach ($user->teams as $team) {
        foreach ($team->rooms as $room) {
            if ($room->channel_id == $channelId) {
                return ['id' => $user->id, 'name' => $user->name, 'avatar' => $user->avatar_url, 'peer_uuid' =>  Str::uuid()];
            }
        }
    }
});*/

/*
Broadcast::channel('peer_status.{channelId}', function ($user, $channelId) {
    foreach ($user->teams as $team) {
        foreach ($team->rooms as $room) {
            if ($room->channel_id == $channelId) {
                return true;
            }
        }
    }
    return false;
});
*/