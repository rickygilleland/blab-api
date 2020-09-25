<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Laravel\Cashier\Billable;

class Organization extends Model
{
    use Billable;

    protected $dates = [
        'created_at',
        'updated_at',
        'trial_ends_at'
    ];
    
    public function teams()
    {
        return $this->hasMany('App\Team');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }
}
