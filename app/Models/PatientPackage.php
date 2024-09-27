<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPackage extends Model
{
    protected $fillable = [
        "user_id","patient_id","package_id","details","question_with_answers"
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}
