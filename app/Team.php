<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function rooms()
    {
        return $this->hasMany('App\Room');
    }
}
