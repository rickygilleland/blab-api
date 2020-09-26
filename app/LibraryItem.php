<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LibraryItem extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function created_by() 
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function attachments()
    {
        return $this->belongsToMany('App\Attachment');
    }
    
}
