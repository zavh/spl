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
        'state'
    ];
    //
    protected $table = 'projects';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
}
