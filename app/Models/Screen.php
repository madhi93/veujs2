<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model 
{

    protected $table = 'screens';
    public $timestamps = true;

    protected $fillable = [
        'title', 
        'short_description',
        'description',
        'status'
    ];

}

