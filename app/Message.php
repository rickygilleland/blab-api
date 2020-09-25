<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function attachments()
    {
        return $this->belongsToMany('App\Attachment');
    }
}
