<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
