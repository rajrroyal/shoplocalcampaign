<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Store extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $guarded = [];

    protected $dates = [
        'created_at'
        ,'updated_at'
        ,'deleted_at'
    ];

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection('logo')
            ->singleFile()
            ->withResponsiveImages();
    }

    public function typeDescription()
    {
        if ($this->source == 'ecwid') {
            return 'PowerShop';
        } else {
            return Str::title($this->source);
        }
    }

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function logo()
    {
        return $this->morphMany(Media::class, 'model')->where('collection_name', 'logo');
    }

}

// model.stub
