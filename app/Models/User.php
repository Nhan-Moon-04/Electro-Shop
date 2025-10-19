<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Bỏ 'HasApiTokens' vì chúng ta dùng JWT
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
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


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Trả về một mảng key/value chứa bất kỳ custom claim nào
     * được thêm vào JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {

        return [
            'name' => $this->user_name,
            'email' => $this->user_email
        ];
    }

    public function getEmailForPasswordReset()
    {
        return $this->user_email;
    }
}