<?php

namespace App;

use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ProductCategory extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'product_categories';
    public $timestamps = true;

    protected $fillable = [
        'name' , 
        'slug' ,  
        'short_description',
        'description',
        'category_id',
        'shop_id',
        'status'
    ];

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