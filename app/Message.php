<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function threads()
    {
        return $this->belongsTo('App\Thread');
    }
}
