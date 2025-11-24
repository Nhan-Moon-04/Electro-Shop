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
        'admin_name',
        'admin_full_name',
        'admin_email',
        'admin_password',
        'admin_role',
        'admin_active',
    ];

    protected $hidden = [
        'admin_password',
    ];

    // JWT Required Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Laravel Auth Methods
    public function getAuthPassword()
    {
        return $this->admin_password;
    }

    public function getEmailForPasswordReset()
    {
        return $this->admin_email;
    }
}
