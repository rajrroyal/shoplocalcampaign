<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;

class ShopcityListing extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'shopcity_01.dbo.listings';
    protected $primaryKey = 'listingid';
    protected $guarded = [];
    public $timestamps = false;

    // Relationships

    public function folder()
    {
        return $this->hasOne(ShopcityFolder::class, 'listingid', 'listingid')
            ->where('status', 1000)
            ->where('ftype', 1);
    }

    public function microsite()
    {
        return $this->hasOne(ShopcityMicrosite::class, 'listingid', 'listingid')
            ->where('active', 1)
            ->orderBy('micrositeid', 'DESC');
    }

    public function microproducts()
    {
        return $this->hasMany(ShopcityMicroproduct::class, 'listingid', 'listingid')
            ->where('status', 1000)
            ->where('deleted', 0);
    }

}

// model.stub
