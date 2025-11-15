<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = false;
    public $incrementing = false; // legacy SQL dump doesn't have AUTO_INCREMENT

    protected $fillable = [
        'order_id',
        'customer_id',
        'staff_id',
        'order_name',
        'order_phone',
        'order_date',
        'order_delivery_date',
        'order_delivery_address',
        'order_note',
        'order_total_before',
        'order_total_after',
        'paying_method_id',
        'order_paying_date',
        'order_is_paid',
        'order_status'
    ];

    // Quan hệ với Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // Quan hệ với Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }

    // Quan hệ với PayingMethod
    public function payingMethod()
    {
        return $this->belongsTo(PayingMethod::class, 'paying_method_id', 'paying_method_id');
    }

    // Quan hệ với OrderDetails
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
}