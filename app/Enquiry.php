<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'project_id',
        'details'
    ];

    protected $table = 'enquiries';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function project()
    {
    	return $this->belongsTo('App\Project');
    }

    public function users(){
        return $this->belongsToMany('App\User');
    }

}
