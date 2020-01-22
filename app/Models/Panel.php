<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model 
{

    protected $table = 'panels';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'no_of_ads', 
        'timer',
        'status'
    ];

    protected $casts = [];

    public function Items()
    {
        return $this->hasMany('App\OrderItem' , 'order_id');
    }

    public function Hub()
    {
        return $this->belongsTo('App\Hub' , 'hub_id');
    }

    public function Customer()
    {
        return $this->belongsTo('App\User' , 'user_id');
    }

    public function update_timeline($status)
    {
       $order = Order::find($this->id);

       if(isset($order) && isset($status)){
           $timeline = $order->timeline;

           if(!is_array($timeline)){
            $timeline =  [];
           }

           switch ($status) {
               case 'order_placed':
                   break;
                case 'order_confirmed':
                    # code...
                    break;
                case 'order_cancelled':
                        # code...
                    break;
                case 'order_packed':
                        # code...
                    break;
                case 'order_shiped':
                        # code...
                    break;
                case 'order_delivered':
                        # code...
                    break;
                    
               
               default:
                   # code...
                   break;
           }

           $timeline[] = [
               'status' => $status,
               'created_at' => Carbon::now()
           ];

           $order->update([ "timeline" => $timeline]);
       }
    }

}
