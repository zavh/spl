<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id', 
        'report_data',
        'stage', 
        'completion', 
        'phone', 
        'acceptance',
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    
}