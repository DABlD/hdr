<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'package_id','name','type', 'category_id', 'code'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function questions(){
        return $this->hasMany('App\Models\Question');
    }
}
