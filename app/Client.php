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
        'deparment_id',
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

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('deptclient', function (Builder $builder) {
                $builder->where('department_id', '=', Auth::User()->department_id);
        });
    }
}
