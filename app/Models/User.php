<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'account_no',
        'contact_no',
        'firstname',
        'lastname',
        'name',          // keep for backward compatibility
        'email',
        'password',
        'role',          // NEW → admin, staff, customer
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
        return $this->hasMany(Order::class);
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
}
