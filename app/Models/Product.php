<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'category_id',
        'supplier_id',
        'product_name',
        'product_avt_img',
        'product_rate',
        'product_description',
        'product_period',
        'product_view_count',
        'product_is_display'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImg::class, 'product_id', 'product_id');
    }

    public function details()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'product_id');
    }

    // Helper: Get giá thấp nhất
    public function getMinPrice()
    {
        return $this->variants()->min('product_variant_price') ?? 0;
    }

    // Helper: Get đường dẫn ảnh đầy đủ
    public function getImagePath($imageName = null)
    {
        $productFolder = "P{$this->product_id}";
        $name = $imageName ?? $this->product_avt_img;
        
        if ($name == 'default.png') {
            return asset('imgs/default.png');
        }
        
        return asset("imgs/product_image/{$productFolder}/{$name}");
    }

    // Helper: Get ảnh đại diện
    public function getAvatarUrl()
    {
        return $this->getImagePath($this->product_avt_img);
    }

    // Helper: Get ảnh đầu tiên từ gallery
    public function getFirstImage()
    {
        $img = $this->images()->where('image_is_display', 1)->first();
        return $img ? $this->getImagePath($img->image_name) : $this->getAvatarUrl();
    }

    // Scope: Chỉ lấy sản phẩm active
    public function scopeActive($query)
    {
        return $query->where('product_is_display', 1);
    }

    // Scope: Chỉ lấy sản phẩm đã xóa
    public function scopeTrashed($query)
    {
        return $query->where('product_is_display', 2);
    }
}