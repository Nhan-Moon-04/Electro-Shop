<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'category_img',
        'category_type',
        'category_added_date',
        'category_is_display'
    ];

    protected $casts = [
        'category_is_display' => 'boolean',
    ];

    // Quan hệ với Products
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}