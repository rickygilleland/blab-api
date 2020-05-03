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
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function validateForPassportPasswordGrant($password)
    {
        $hashed_password = Hash::make($password);
        $code = \App\LoginCode::where('code', $hashed_password)->first();
        
        if (!$code || $code->user_id != $this->id || $code->used 
            || (strtotime($code->created_at) + 3600) < time()) {
            return false;
        }
        
        $code->used = true;
        $code->save();

        return true;
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

}