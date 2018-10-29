<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'task_name', 
        'task_description',
        'task_date_assigned',
        'task_deadline', 
    ];
    //
    protected $table = 'tasks';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function users(){
        return $this->belongsToMany('App\User');
    }

    public function project(){
        return $this->belongsTo('App\Project');
    }
}
