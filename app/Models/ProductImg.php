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
}
