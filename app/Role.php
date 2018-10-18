<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role_name', 
        'role_description'
    ];

    public function users(){
        return $this->belongsToMany('App\User');
    }
}
