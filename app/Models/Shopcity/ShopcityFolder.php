<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;

class ShopcityFolder extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'administrator.dbo.subfolders';
    protected $primaryKey = 'folderid';
    protected $guarded = [];
    public $timestamps = false;

    // Relationships

    public function listing()
    {
        return $this->belongsTo(ShopcityListing::class, 'listingid', 'listingid')
            ->where('active', 1)
            ->where('deleted', 0);
    }

}

// model.stub
