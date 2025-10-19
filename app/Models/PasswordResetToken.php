<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'MaNguoiDung',
        'Token',
        'ExpireAt',
        'Used'
    ];

    protected $casts = [
        'ExpireAt' => 'datetime',
        'Used' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'MaNguoiDung', 'user_id');
    }
}
