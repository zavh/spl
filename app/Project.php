<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'project_name', 
        'client_id',
        'manager_id',
        'assigned', 
        'deadline',
        'description', 
        'status', 
        'state',
        'allocation'
    ];
    //
    protected $table = 'projects';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function enquiries()
    {
    	return $this->hasMany('App\Enquiry');
    }

    public function client()
    {
    	return $this->belongsTo('App\Client');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tasks(){
        return $this->hasMany('App\Task');
    }
}
