<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hmo_provider',
        'hmo_number',
        'patient_id',
        'mothers_name',
        'fathers_name',
        'guardian_name',
        'employment_status',
        'company_name',
        'company_position',
        'company_contact',
        'sss',
        'tin_number'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function packages(){
        return $this->hasMany('App\Models\Package', 'company', 'company_name');
    }

    public function exams(){
        return $this->hasMany('App\Models\PatientPackage');
    }
}