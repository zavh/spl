<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Department extends Model
{
    //
    protected $table = 'departments';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function users(){
        return $this->hasMany('App\User');
    }

    public function product(){
        return $this->hasOne('App\Product');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('exapp', function (Builder $builder) {
            if(Auth::User()->role->role_name != 'superadmin')
                $builder->where('name', '<>' ,'Application');
        });
    }
}
