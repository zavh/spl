<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'project_id',
        'details'
    ];

    public function project()
    {
    	return $this->belongsTo('App\Project');
    }
}
