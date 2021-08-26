<?php

namespace App\Models;

use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected $dates = [
        'created_at'
        ,'updated_at'
    ];

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection('main-image')
            ->singleFile()
            ->withResponsiveImages();

        $this
            ->addMediaCollection('images')
            ->withResponsiveImages();

    }

    // Relationships

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function mainImage()
    {
        return $this->morphMany(Media::class, 'model')->where('collection_name', 'main-image');
    }

    public function images()
    {
        return $this->morphMany(Media::class, 'model')->where('collection_name', 'images');
    }
}

// model.stub
