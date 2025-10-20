<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'is_active',
    ];


    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
