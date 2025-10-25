<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'name',
        'email',
        'contact',
        'address',
        'gallon_type',
        'gallon_count',
        'role',
        'password',
        'approval_status',
        'confirmation_code',
        'qr_token',
        'qr_code',
    ];



    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'isValidated' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Existing Relations (billing system)
    |--------------------------------------------------------------------------
    */
    public function property_types()
    {
        return $this->hasOne(PropertyTypes::class, 'id', 'property_type');
    }

    public function accounts()
    {
        return $this->hasMany(UserAccounts::class);
    }

    /*
    |--------------------------------------------------------------------------
    | New Relations (water delivery system)
    |--------------------------------------------------------------------------
    */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'staff_id');
    }

    public function isRole($role)
    {
        return $this->role === $role;  // Compare the user's role with the passed role
    }

    public function borrowedGallons()
    {
        return $this->hasMany(\App\Models\BorrowedGallon::class, 'user_id');
    }

}
