<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImg extends Model
{
    protected $table = 'product_imgs';
    protected $primaryKey = 'image_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'image_id',
        'product_id',
        'image_name',
        'image_is_display'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Accessor để lấy đường dẫn ảnh đầy đủ
    public function getImageUrlAttribute()
    {
        if (!$this->product) {
            return asset('imgs/default.png');
        }

        $productFolder = "P{$this->product_id}";

        if ($this->image_name == 'default.png') {
            return asset('imgs/default.png');
        }

        return asset("imgs/product_image/{$productFolder}/{$this->image_name}");
    }
}
