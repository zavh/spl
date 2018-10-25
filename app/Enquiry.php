<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    public function employee()
    {
    	return $this->belongsTo('App\Project');
    }
}
