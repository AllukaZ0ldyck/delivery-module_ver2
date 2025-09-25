<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'delivery_address',
        'delivery_date',
        'status',
        'delivery_status', // New field to track delivery status
        'payment_status',   // New field to track payment status
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Update the order delivery status
    public function updateDeliveryStatus($status)
    {
        $this->delivery_status = $status;
        $this->save();
    }

    // Update the order payment status
    public function updatePaymentStatus($status)
    {
        $this->payment_status = $status;
        $this->save();
    }
}
