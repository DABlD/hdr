<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamList extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "user_id",'doctor_id','type'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function attending_doctor(){
        return $this->belongsTo('App\Models\User', 'doctor_id', 'id')->withTrashed();
    }
}
