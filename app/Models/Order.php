<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

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
        'payment_method',
        'payment_receipt',
        'payment_status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

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

    public function deliveryPersonnel()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'delivery_personnel_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }



}
