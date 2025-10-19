<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use App\Models\EmailVerificationToken;
use App\Mail\AccountVerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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


        $credentials = [
            'user_email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        // 3. Tiến hành attempt với credentials đã sửa
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        // Nếu user chưa active (chưa xác nhận email) thì chặn login
        $user = auth('api')->user();
        if ($user && isset($user->user_active) && !$user->user_active) {
            // logout to invalidate token
            auth('api')->logout();
            return response()->json(['error' => 'Tài khoản chưa được xác nhận email. Vui lòng kiểm tra email để xác nhận.'], 403);
        }

        return $this->createNewToken($token);
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
        auth('api')->logout();
        return response()->json(['message' => 'Đăng xuất thành công']);
    }


    // refresh token
    public function refresh()
    {
        return $this->createNewToken(auth('api')->refresh());
    }

    // thông tin user
    public function userProfile()
    {

        return response()->json($this->getFormattedUser(auth('api')->user()));
    }


    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $this->getFormattedUser(auth('api')->user())
        ]);
    }


    protected function getFormattedUser($user)
    {
        if (!$user) {
            return null;
        }

        return [
            'id' => $user->user_id,
            'name' => $user->user_name,
            'email' => $user->user_email

        ];
    }
}