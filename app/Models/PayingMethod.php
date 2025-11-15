<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayingMethod extends Model
{
    use HasFactory;

    protected $table = 'paying_methods';
    protected $primaryKey = 'paying_method_id';
    public $timestamps = false;

    protected $fillable = [
        'paying_method_name',
        'paying_method_is_display'
    ];

    protected $casts = [
        'paying_method_is_display' => 'boolean',
    ];

    // Quan hệ với Orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'paying_method_id', 'paying_method_id');
    }
}