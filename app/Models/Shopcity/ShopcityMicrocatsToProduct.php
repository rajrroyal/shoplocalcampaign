<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;

class ShopcityMicrocatsToProduct extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'shopcity_01.dbo.microcats_to_products';
    protected $guarded = [];
    public $timestamps = false;

    // Relationships

    public function microproduct()
    {
        return $this->belongsTo(ShopcityMicroproduct::class, 'productid', 'productid');
    }

    public function microcat()
    {
        return $this->belongsTo(ShopcityMicrocat::class, 'catid', 'mcatid')
            ->where('status', 1000);
    }
}

// model.stub
