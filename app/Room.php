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
}
