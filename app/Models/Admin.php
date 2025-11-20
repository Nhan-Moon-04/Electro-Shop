<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;

    protected $fillable = [
        'admin_login_name',
        'admin_password',
        'admin_name',
        'admin_full_name',
        'admin_avt_img',
        'admin_birth',
        'admin_sex',
        'admin_email',
        'admin_phone',
        'admin_address',
        'admin_role',
        'admin_active',
    ];

    protected $hidden = [
        'admin_password',
        'remember_token',
    ];

    protected $casts = [
        'admin_birth' => 'date',
        'admin_active' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->admin_password;
    }

    public function getEmailForPasswordReset()
    {
        return $this->admin_email;
    }

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->admin_name,
            'email' => $this->admin_email,
            'role' => $this->admin_role,
            'user_type' => 'admin'
        ];
    }
}
