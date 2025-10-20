<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedGallon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'gallon_count',
        'gallon_type',
        'borrowed_at',
        'due_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
