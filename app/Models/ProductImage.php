<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_imgs';
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'image_name',
        'image_is_display'
    ];

    protected $casts = [
        'image_is_display' => 'boolean',
    ];

    // Quan hệ ngược lại với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}