<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;

class ShopcityMicroproductImage extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'shopcity_01.dbo.microproducts_extra_images';
    protected $primaryKey = 'imageid';
    protected $guarded = [];
    public $timestamps = false;

    public function mainImageSrc()
    {
        return 'https://secure.shopcity.com/tools/products/photos/' . $this->productid . '.jpg';
    }

    public function extraImageSrc()
    {
        return 'https://secure.shopcity.com/tools/products/extra_images/' . $this->productid . '_' . $this->imageid . '.jpg';
    }

    // Relationships

    public function product()
    {
        return $this->belongsTo(ShopcityMicroproduct::class, 'productid', 'productid');
    }

}

// model.stub
