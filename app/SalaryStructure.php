<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    //
    protected $table = 'salarystructures';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

}
