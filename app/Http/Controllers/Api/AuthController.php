<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Admin;
use Carbon\Carbon;
use App\Models\EmailVerificationToken;
use App\Mail\AccountVerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // đăng nhập
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $token = null;
        $user = null;
        $userType = null;

        // Thử đăng nhập với bảng admin trước
        $admin = Admin::where('admin_email', $email)->first();
        if ($admin && Hash::check($password, $admin->admin_password)) {
            if (!$admin->admin_active) {
                return response()->json(['error' => 'Tài khoản admin đã bị vô hiệu hóa'], 403);
            }
            $token = JWTAuth::fromUser($admin);
            $user = $admin;
            $userType = 'admin';
        } else {
            // Nếu không phải admin, thử đăng nhập với bảng user
            $normalUser = User::where('user_email', $email)->first();
            if ($normalUser && Hash::check($password, $normalUser->user_password)) {
                if (!$normalUser->user_active) {
                    return response()->json(['error' => 'Tài khoản chưa được xác nhận email. Vui lòng kiểm tra email để xác nhận.'], 403);
                }
                $token = JWTAuth::fromUser($normalUser);
                $user = $normalUser;
                $userType = 'user';
            }
        }

        if (!$token) {
            return response()->json(['error' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        return $this->createNewToken($token, $user, $userType);
    }


    // đăng ký
    public function register(Request $request)
    {
        // 1. Sửa Validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users,user_email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        // create inactive user
        $user = User::create([
            'user_name' => $request->name,
            'user_email' => $request->email,
            'user_password' => Hash::make($request->password),
            'user_register_date' => Carbon::now(),
            'user_active' => 0,
            'user_login_name' => '0000000000',
            'user_phone' => '0000000000',
        ]);

        // create verification token
        $token = bin2hex(random_bytes(32));
        $expireAt = now()->addMinutes(60);

        EmailVerificationToken::create([
            'MaNguoiDung' => $user->user_id,
            'Token' => $token,
            'ExpireAt' => $expireAt,
            'Used' => false,
        ]);

        // send verification email
        try {
            Mail::to($user->user_email)->send(new AccountVerificationMail($user->user_email, $token));
        } catch (\Exception $e) {
            // Log and let user know to contact admin
            Log::error('Failed to send account verification email: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể gửi email xác nhận. Vui lòng thử lại sau.'], 500);
        }

        return response()->json([
            'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để xác nhận tài khoản.'
        ], 201);
    }


    // đăng xuất
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Đăng xuất thành công']);
    }


    // refresh token
    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        $user = JWTAuth::user();
        $userType = $user instanceof Admin ? 'admin' : 'user';
        return $this->createNewToken($token, $user, $userType);
    }

    // thông tin user
    public function userProfile()
    {
        $user = JWTAuth::user();
        return response()->json($this->getFormattedUser($user));
    }


    protected function createNewToken($token, $user, $userType)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => $this->getFormattedUser($user),
            'user_type' => $userType,
            'redirect_url' => $userType === 'admin' ? '/admin' : '/'
        ]);
    }


    protected function getFormattedUser($user)
    {
        if (!$user) {
            return null;
        }

        if ($user instanceof Admin) {
            return [
                'id' => $user->admin_id,
                'name' => $user->admin_name,
                'full_name' => $user->admin_full_name,
                'email' => $user->admin_email,
                'role' => $user->admin_role,
                'user_type' => 'admin'
            ];
        } else {
            return [
                'id' => $user->user_id,
                'name' => $user->user_name,
                'email' => $user->user_email,
                'user_type' => 'user'
            ];
        }
    }
}