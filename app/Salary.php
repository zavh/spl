<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    //
    protected $table = 'salaries';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'user_id', 
        'salaryinfo',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function loans(){
        return $this->hasMany('App\Loan');
    }
    
    public function scopeActivesalary($query)
    {
        return $query->where('salaryinfo->tstatus', 'a');
    }
}
