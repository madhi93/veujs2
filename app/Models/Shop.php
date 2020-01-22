<?php

namespace App;

use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Shop extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'shops';
    public $timestamps = true;

    protected $fillable = [
        'name' , 
        'shop_no' ,  
        'short_description' ,
        'category_id' , 
        'description',
        'status'
    ];

    public function Category()
    {
        return $this->belongsTo('App\ShopCategory' , 'category_id');
    }

    public function Products()
    {
        return $this->belongsTo('App\Product' , 'category_id');
    }


    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
            
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

}