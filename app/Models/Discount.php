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
}