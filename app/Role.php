<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role_name', 
        'role_description'
    ];

    
    protected $table = 'roles';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function users(){
        return $this->hasMany('App\User');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
