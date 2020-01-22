<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsPanel extends Model 
{

    protected $table = 'ads_panels';
    public $timestamps = true;

    protected $fillable = [
        'panel_id', 
        'ads_id',
        'timer',
        'order_column', 
        'status'
    ];

    public function Order()
    {
        return $this->belongsTo('App\Order' , 'order_id');
    }

    public function Product()
    {
        return $this->belongsTo('App\Product' , 'product_id');
    }

}

