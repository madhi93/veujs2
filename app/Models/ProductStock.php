<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model 
{

    protected $table = 'product_stocks';
    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'unit',         
        'quantity', 
        'in_stock',
        'hub_id', 
        'status'
    ];

    public function Product()
    {
        return $this->belongsTo('App\Product' , 'product_id');
    }

    public function Hub()
    {
        return $this->belongsTo('App\Hub' , 'hub_id');
    }

}
