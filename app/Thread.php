<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function organizations()
    {
        return $this->belongsToMany('App\Organization');
    }
    
    public function teams()
    {
        return $this->belongsToMany('App\Team');
    }
    
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
