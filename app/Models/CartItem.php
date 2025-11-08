<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'carts';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'product_variant_id',
        'cart_quantity',
        'cart_added_date',
    ];

    protected $casts = [
        'cart_added_date' => 'datetime',
        'cart_quantity' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'user_id');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

}