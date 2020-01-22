<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsDate extends Model 
{

    protected $table = 'ads_dates';
    public $timestamps = true;

    protected $fillable = [
        'ads_date', 
        'start_at',
        'ends_at',
        'ads_id',
        'active_status',
        'status'
    ];

}

