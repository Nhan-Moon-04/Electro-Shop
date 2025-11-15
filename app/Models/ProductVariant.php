<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';
    protected $primaryKey = 'product_variant_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'discount_id',
        'product_variant_name',
        'product_variant_price',
        'product_variant_available',
        'product_variant_is_stock',
        'product_variant_is_bestseller',
        'product_variant_added_date',
        'product_variant_is_display'
    ];

    protected $casts = [
        'product_variant_price' => 'decimal:2',
        'product_variant_available' => 'integer',
        'product_variant_is_stock' => 'boolean',
        'product_variant_is_bestseller' => 'boolean',
        'product_variant_is_display' => 'boolean',
    ];

    // Quan hệ ngược lại với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Quan hệ với Discount (nếu có)
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'discount_id');
    }
    // Quan hệ với OrderDetails
public function orderDetails()
{
    return $this->hasMany(OrderDetail::class, 'product_variant_id', 'product_variant_id');
}
}