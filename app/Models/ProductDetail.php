<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $table = 'product_details';
    protected $primaryKey = 'product_detail_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'product_detail_name',
        'product_detail_value',
        'product_detail_unit'
    ];

    // Quan hệ ngược lại với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}