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
        'patient_id'
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
}