<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //
    protected $table = 'loans';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'salary_id',
        'amount',
        'start_date',
        'end_date',
        'installments',
        'loan_type',
        'interest'
    ];

    public function salary(){
        return $this->belongsTo('App\Salary');
    }
}
