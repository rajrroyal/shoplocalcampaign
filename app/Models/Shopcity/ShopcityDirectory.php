<?php

namespace App\Models\Shopcity;

use Illuminate\Database\Eloquent\Model;

class ShopcityDirectory extends Model
{
    protected $connection = 'shopcity';
    protected $table = 'administrator.dbo.shopsites';
    protected $primaryKey = 'directoryid';
    protected $guarded = [];
    public $timestamps = false;

}

// model.stub
