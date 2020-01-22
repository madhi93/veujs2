<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model 
{

    protected $table = 'site_settings';
    public $timestamps = true;

    protected $fillable = [
        'key',
        'value',
        'user_id',
        'status'
    ];

}