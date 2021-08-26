<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;

class ShopcityMicroproduct extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'shopcity_01.dbo.microproducts';
    protected $primaryKey = 'productid';
    protected $guarded = [];
    public $timestamps = false;

    // Relationships

    public function listing()
    {
        return $this->belongsTo(ShopcityListing::class, 'listingid', 'listingid');
    }

    public function images()
    {
        return $this->hasMany(ShopcityMicroproductImage::class, 'productid', 'productid')
            ->where('active', 1);
    }

    public function category()
    {
        return $this->hasOne(ShopcityCategory::class, 'categoryid', 'maincatid');
    }

    public function microcatsToProduct()
    {
        return $this->hasMany(ShopcityMicrocatsToProduct::class, 'productid', 'productid');
    }
}

// model.stub
