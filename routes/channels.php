<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('user.{userId}', function ($user, $userId) {
    if ($user->id === $userId) {
      return array('name' => $user->name);
    }
});

Broadcast::channel('chat.{channelId}', function ($user, $channelId) {
    foreach ($user->teams as $team) {
        foreach ($team->rooms as $room) {
            if ($room->channel_id == $channelId) {
                return ['id' => $user->id, 'name' => $user->name, 'avatar' => $user->avatar_url];
            }
        }
    }
});

Broadcast::channel('{channelId}', function ($user, $channelId) {
    foreach ($user->teams as $team) {
        foreach ($team->rooms as $room) {
            if ($room->channel_id == $channelId) {
                return true;
            }
        }
    }
    return false;
});