<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('excfg', function (Builder $builder) {
            $builder->where('structurename', '<>' ,'config');
        });
    }
}
