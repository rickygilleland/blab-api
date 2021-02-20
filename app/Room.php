<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function team()
    {
        return $this->belongsTo('App\Team');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function thread()
    {
        return $this->hasOne('App\Thread');
    }

    public function active_users()
    {
        return $this->hasMany('App\User', 'current_room_id');
    }
}
