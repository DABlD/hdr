<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        "name", "value", 'clinic'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}