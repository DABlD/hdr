<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\WellnessAttribute;

class Wellness extends Model
{
    use SoftDeletes, WellnessAttribute;

    protected $table = 'wellness';

    protected $fillable = [
        "company",
        "recommendation",
        "files"
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function company(){
        return $this->hasOne('App\Models\User', 'company', 'fname');
    }
}