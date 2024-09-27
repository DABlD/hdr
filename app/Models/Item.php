<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "code","category","brand","name","packaging","amount","reorder","stock"
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
}
