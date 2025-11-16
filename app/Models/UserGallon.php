<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGallon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gallon_type',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
