<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable , HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 
        'last_name', 
        'email', 
        'mobile' , 
        'std_code' , 
        'gender', 
        'password',
        'locale',
        'mobile_verified_at',
        'email_verified_at'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_customer', 'is_driver' 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

    protected $appends = ["fullname"];

    public function getFullNameAttribute() 
    {
        return ucfirst(strtolower($this->first_name)) .' ' . ucfirst(strtolower($this->last_name));
    } 

    public function Location()
    {
        return $this->hasOne('App\UserLocation' , 'user_id');
    }

    public function Bookings()
    {
        return $this->hasMany('App\Booking' , 'customer_id');
    }

    public function UserDevices()
    {
        return $this->hasMany('App\UserDevice' , 'user_id');
    }

    public function Bookmarks()
    {
        return $this->hasMany('App\Bookmark' , 'customer_id');
    }

}
