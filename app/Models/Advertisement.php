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
        'is_expired',
        'shop_id',
        'status'
    ];

}

