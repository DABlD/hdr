<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientPackage extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        "user_id","patient_id","package_id","details","question_with_answers", 'remarks', 'type', 'file', 'recommendation', 'clinical_assessment', 'classification','c_remarks','doctor_id','status','vitals', 'diagnostics', 'evaluation'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function package(){
        return $this->belongsTo('App\Models\Package')->withTrashed();
    }

    public function user(){
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function doctor(){
        return $this->belongsTo('App\Models\User');
    }
}
