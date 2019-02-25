<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    protected $fillable = [
        'organization', 
        'address',
        'background',
        'department_id',
    ];
    protected $table = 'clients';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
	
	public function projects(){
        return $this->hasMany('App\Project');
    }

    public function clientcontacts(){
        return $this->hasMany('App\Clientcontact');
    }

    public function department(){
        return $this->belongsTo('App\Department');
    }

    public function reports(){
        return $this->hasMany('App\Report');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('deptclient', function (Builder $builder) {
                $builder->where('department_id', '=', Auth::User()->department_id);
        });
    }
}
