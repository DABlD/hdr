<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nurse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'sss',
        'tin',
        'philhealth',
        'pagibig'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function doctor(){
        return $this->belongsTo('App\Models\Doctor');
    }
}
