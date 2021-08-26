<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopcityMicrosite extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'shopcity_01.dbo.microsites';
    protected $primaryKey = 'micrositeid';
    protected $guarded = [];
    public $timestamps = false;

}

// model.stub
