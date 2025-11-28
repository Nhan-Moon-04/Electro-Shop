<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';
    protected $primaryKey = 'discount_id';
    public $timestamps = false;

    protected $fillable = [
        'discount_name',
        'discount_description',
        'discount_start_date',
        'discount_end_date',
        'discount_amount',
        'discount_is_display',
        'discount_img'
    ];

    protected $casts = [
        'discount_start_date' => 'datetime',
        'discount_end_date' => 'datetime',
        'discount_amount' => 'decimal:2',
        'discount_is_display' => 'boolean',
    ];

    // Quan hệ với ProductVariants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'discount_id', 'discount_id');
    }

    // Check if discount is active
    public function isActive()
    {
        $now = now();
        return $this->discount_is_display 
            && $now->greaterThanOrEqualTo($this->discount_start_date)
            && $now->lessThanOrEqualTo($this->discount_end_date);
    }

    // Get status badge HTML
    public function getStatusBadge()
    {
        if ($this->isActive()) {
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Đang áp dụng</span>';
        } elseif (now()->lessThan($this->discount_start_date)) {
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Sắp diễn ra</span>';
        } elseif (now()->greaterThan($this->discount_end_date)) {
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Đã kết thúc</span>';
        } else {
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Đã ẩn</span>';
        }
    }
}