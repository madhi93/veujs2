<?php

namespace App;

use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ShopCategory extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'shop_categories';
    public $timestamps = true;

    protected $fillable = [
        'name' , 
        'slug' ,  
        'short_description',
        'description',
        'status'
    ];

    public function Shops()
    {
        return $this->belongsTo('App\Shop' , 'category_id');
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