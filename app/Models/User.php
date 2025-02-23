<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\UserAttribute;

use Hash;

class User extends Authenticatable
{
    use SoftDeletes, UserAttribute;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role', 'fname', 'mname', 'suffix', 
        'lname', 'email', 'birthday', 
        'gender', 'address', 'contact', 
        'password', 'civil_status',
        'username', 'nationality', 'religion',
        'avatar', 'birth_place',
        'kyc_id', 'prefix', 'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'birthday'
    ];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    public function patient(){
        return $this->hasOne('App\Models\Patient');
    }

    public function doctor(){
        return $this->hasOne('App\Models\Doctor');
    }

    public function nurse(){
        return $this->hasOne('App\Models\Nurse');
    }

    public function receptionist(){
        return $this->hasOne('App\Models\Receptionist');
    }

    public function imaging(){
        return $this->hasOne('App\Models\Imaging');
    }

    public function laboratory(){
        return $this->hasOne('App\Models\Laboratory');
    }
}