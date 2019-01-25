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
        'organization',		
        'visit_date',
        'client_id'
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function client(){
        return $this->belongsTo('App\Client');
    }
    public function project(){
        return $this->hasOne('App\Project');
    }    
}