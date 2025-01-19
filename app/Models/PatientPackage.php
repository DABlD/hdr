<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPackage extends Model
{
    protected $fillable = [
        "user_id","patient_id","package_id","details","question_with_answers", 'remarks', 'type', 'file', 'recommendation', 'clinical_assessment', 'classification','c_remarks','doctor_id','status','vitals', 'diagnostics'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function package(){
        return $this->belongsTo('App\Models\Package')->withTrashed();
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function doctor(){
        return $this->belongsTo('App\Models\User');
    }
}
