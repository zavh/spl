<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    protected $fillable = [
        'structurename', 
        'structure',
    ];

    protected $table = 'salarystructures';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function users(){
        return $this->hasMany('App\User','salaryprofile');
    }
}
