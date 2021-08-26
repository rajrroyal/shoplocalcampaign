<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;

class ShopcityMicrocat extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'shopcity_01.dbo.microcats';
    protected $primaryKey = 'mcatid';
    protected $guarded = [];
    public $timestamps = false;

    // Relationships

}

// model.stub
