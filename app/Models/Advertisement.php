<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model 
{

    protected $table = 'advertisements';
    public $timestamps = true;

    protected $fillable = [
        'title', 
        'short_description',
        'description',
        'type',
        'start_at',
        'ends_at',
        'days',
        'shop_id',
        'active_status',
        'status'
    ];

}

