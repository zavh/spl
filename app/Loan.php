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
        'loan_name',
        'start_date',
        'end_date',
        'tenure',
        'loan_type',
        'interest',
        'active',
    ];

    public function salary(){
        return $this->belongsTo('App\Salary');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
