<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScreenPanel extends Model 
{

    protected $table = 'screen_panels';
    public $timestamps = true;

    protected $fillable = [
        'screen_id', 
        'panel_id',
        'status'
    ];

}

