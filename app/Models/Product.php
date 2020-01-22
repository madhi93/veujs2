<?php

namespace App;

use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Product extends Model  implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'products';
    public $timestamps = true;

    protected $fillable = [
        'name', 
        'short_description',  
        'description',
        'category_id',
        'shop_id', 
        'status'
    ];

    public function Category()
    {
        return $this->belongsTo('App\Category' , 'category_id');
    }

    public function Stock()
    {
        return $this->hasOne('App\ProductStock' , 'product_id');
    }

    public function Stocks()
    {
        return $this->haMany('App\Stock' , 'product_id');
    }

    public function Purchses()
    {
        return $this->hasMany('App\Purchse' , 'product_id');
    }

    public function update_stock($product_id)
    {
       
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);

        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
    }

}
