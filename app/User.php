<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'role_id',
        'email', 
        'password', 
        'phone', 
        'address',
        'active',
        'department_id',
        'designation_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

   /* public function findForPassport($identifier) {
        return User::orWhere('email', $identifier)->where('status', 1)->first();
    } */
    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function department(){
        return $this->belongsTo('App\Department');
    }

    public function designation(){
        return $this->belongsTo('App\Designation');
    }

    public function tasks(){
        return $this->belongsToMany('App\Task');
    }

    public function reports(){
        return $this->hasMany('App\Report');
    }
}
