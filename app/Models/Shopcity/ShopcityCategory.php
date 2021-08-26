<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;

class ShopcityCategory extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'administrator.dbo.categories';
    protected $primaryKey = 'categoryid';
    protected $guarded = [];
    public $timestamps = false;

    // Relationships

}

// model.stub
