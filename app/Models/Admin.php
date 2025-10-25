<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guard = 'admin'; 

    protected $fillable = [
        'name',
        'email',
        'user_type',
        'password',
        'token',
        'phone',
        'address',
        'profile_picture',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
}

