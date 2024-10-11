<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        "user_id", "sss","tin","philhealth","pagibig","license_number","s2_number","ptr","specialization","pharma_partner","title","medical_association","diplomate","signature"
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
