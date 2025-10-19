<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;

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


        $user = User::create([
            'user_name' => $request->name,
            'user_email' => $request->email,
            'user_password' => Hash::make($request->password),
            'user_register_date' => Carbon::now(),
            'user_active' => 1,
            'user_login_name' => '0000000000',
            'user_phone' => '0000000000',
        ]);

        return response()->json([
            'message' => 'Đăng ký tài khoản thành công!',

            'user' => $this->getFormattedUser($user)
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