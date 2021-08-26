<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $table = 'error_log';

    public $timestamps = false;

    protected $guarded = [];

    protected $dates = [
        'created_at'
        ,'updated_at'
    ];

}

// model.stub
