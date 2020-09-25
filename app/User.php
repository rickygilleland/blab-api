<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'provider', 'provider_id', 'created_at', 'email_verified_at', 'streamer_key',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'last_login_at'
    ];

    public function validateForPassportPasswordGrant($password)
    {
        foreach ($this->loginCodes as $code) {
            if (Hash::check($password, $code->code)) {
                if ($code->user_id != $this->id || $code->used 
                    || (strtotime($code->created_at) + 3600) < time()) {
                    return false;
                }
                
                $code->used = true;
                $code->save();

                return true;
            }
        }
        
        return false;
    }

    public function roles()
    {
	    return $this->belongsToMany('App\Role');
    }
    
    public function hasRole($role)
    {
	    
	    foreach ($this->roles as $user_role) {
		    
		    if ($user_role->name == $role) {
			    return true;
		    }
		    
		    if ($user_role->name == "system_admin") {
			    //let them do whatever they want
			    return true;
		    }
	    }
	    
	    return false;
    }

    public function loginCodes() {
        return $this->hasMany('App\LoginCode');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function rooms()
    {
        return $this->belongsToMany('App\Room');
    }

    public function threads()
    {
        return $this->belongsToMany('App\Thread');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

}