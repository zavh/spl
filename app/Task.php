<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = [
        'task_name', 
        'task_description',
        'task_date_assigned',
        'task_deadline',
        'weight',
        'completed',
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

    protected static function boot()
    {
        parent::boot();
    
        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('task_deadline', 'asc');
        });
    }
}
