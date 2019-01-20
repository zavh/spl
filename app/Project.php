<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    protected $fillable = [
        'project_name', 
        'client_id',
        'manager_id',
        'assigned', 
        'deadline',
        'description',
        'department_id',
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

    public function department(){
        return $this->belongsTo('App\Department');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('deptproj', function (Builder $builder) {
                $builder->where('department_id', Auth::User()->department_id);
        });
    }
}
