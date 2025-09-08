<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','amount','discount','until', 'company'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'until', 'deleted_at'
    ];

    public function questions(){
        return $this->hasMany('App\Models\Question');
    }

    public function ongoingTransactions(){
        return $this->hasMany('App\Models\Transaction')->where('status', 'Ongoing');
    }
}