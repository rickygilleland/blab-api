<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    public function messages()
    {
        return $this->belongsToMany('App\Message');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function libraryItem()
    {
        return $this->belongsTo('App\LibraryItem');
    }
    
}
