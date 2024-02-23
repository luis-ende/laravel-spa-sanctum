<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function wallet() {

        return $this->hasOne('App\Models\Wallet');
    }

    public function investments() {

        return $this->hasMany('App\Models\Investment', 'user_id');
    }
}
