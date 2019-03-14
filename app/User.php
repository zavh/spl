<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        'designation_id',
        'salaryprofile',
        'joindate',
        'dob',
        'gender',
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
    public function salary(){
        return $this->hasOne('App\Salary');
    }
    public function salarystructure(){
        return $this->belongsTo('App\SalaryStructure', 'salaryprofile');
    }
    public function scopeActual($query)
    {
        return $query->where('department_id', '<>', 1);
    }
    public function scopeDept($query)
    {
        return $query->where('department_id',  Auth::User()->department_id);
    }
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

}
