<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientcontact extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'contact',
        'client_id',
    ];

    public function client(){
        return $this->belongsTo('App\Client');
    }
}
