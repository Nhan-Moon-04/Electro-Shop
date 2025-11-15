<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'staff_role',
        'staff_description',
        'staff_added_date'
    ];

    // Quan hệ với Products
    public function Users()
    {
        return $this->hasMany(User::class, 'staff_id', 'staff_id');
    }
      // Quan hệ với Orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'staff_id', 'staff_id');
    }
}