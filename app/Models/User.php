<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable // Tạm bỏ implements JWTSubject
{
    use HasFactory, Notifiable;


    protected $table = 'users';


    protected $primaryKey = 'user_id';


    public $timestamps = false;


    protected $fillable = [
        'user_name',
        'user_email',
        'user_password',
        'user_register_date',
        'user_active',
        'user_login_name',
        'user_phone',
    ];


    protected $hidden = [
        'user_password',
        'remember_token',
    ];


    protected $casts = [

        'user_register_date' => 'datetime',
    ];


    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function getEmailForPasswordReset()
    {
        return $this->user_email;
    }
    
    // JWT methods - uncomment khi cài đặt JWT package
    /*
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->user_name,
            'email' => $this->user_email
        ];
    }
    */
}