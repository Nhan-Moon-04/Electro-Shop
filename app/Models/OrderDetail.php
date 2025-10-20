<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'order_detail_quantity',
        'order_detail_price_before',
        'order_detail_price_after'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

}
