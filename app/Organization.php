<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Laravel\Cashier\Billable;

class Organization extends Model
{
    use Billable;
    
    public function teams()
    {
        return $this->hasMany('App\Team');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
