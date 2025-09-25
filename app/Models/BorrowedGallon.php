<?php
// app/Models/BorrowedGallon.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedGallon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gallon_count',
        'borrowed_at',
        'due_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
