<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'product_name',
        'supplier_id',
        'product_avt_img',
        'product_rate',
        'product_description',
        'product_period',
        'product_view_count',
        'product_is_display'
    ];

    protected $casts = [
        'product_rate' => 'decimal:1',
        'product_view_count' => 'integer',
        'product_is_display' => 'boolean',
    ];

    // Quan hệ với category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // Quan hệ với supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    // Quan hệ với product_images
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    // Quan hệ với product_variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }

    // Quan hệ với product_details
    public function details()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'product_id');
    }

    // Scope: chỉ lấy sản phẩm đang hiển thị
    public function scopeDisplayed($query)
    {
        return $query->where('product_is_display', 1);
    }

    // Lấy giá thấp nhất từ variants
    public function getMinPriceAttribute()
    {
        return $this->variants()->min('product_variant_price') ?? 0;
    }

    // Lấy giá cao nhất từ variants
    public function getMaxPriceAttribute()
    {
        return $this->variants()->max('product_variant_price') ?? 0;
    }

    // Kiểm tra có khuyến mãi không
    public function getDiscountedVariantsAttribute()
    {
        return $this->variants()->where('discount_id', '!=', null)->get();
    }
}