<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
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
}
