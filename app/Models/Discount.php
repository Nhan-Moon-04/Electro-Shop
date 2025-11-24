<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $primaryKey = 'discount_id';
    public $timestamps = false;

    protected $fillable = [
        'discount_name',
        'discount_description',
        'discount_start_date',
        'discount_end_date',
        'discount_amount',
        'discount_is_display'
    ];

    protected $casts = [
        'discount_start_date' => 'date',
        'discount_end_date' => 'date',
        'discount_is_display' => 'boolean'
    ];

    // Relationship với orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_id', 'discount_id');
    }

    // Relationship với product_variants
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'discount_id', 'discount_id');
    }

    // Check if discount is active
    public function isActive()
    {
        return $this->discount_is_display 
            && now()->between($this->discount_start_date, $this->discount_end_date);
    }

    // Check if discount is expired
    public function isExpired()
    {
        return now()->gt($this->discount_end_date);
    }

    // Get status badge
    public function getStatusBadge()
    {
        if ($this->isExpired()) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">Hết hạn</span>';
        }
        
        if ($this->isActive()) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-700">Đang hoạt động</span>';
        }
        
        if (now()->lt($this->discount_start_date)) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-200 text-blue-700">Sắp diễn ra</span>';
        }

        return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-700">Không hoạt động</span>';
    }
}