<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Admin;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\EmailVerificationToken;
use App\Mail\AccountVerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

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
        // 1. Sửa Validator với custom messages
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users,user_email',
            'password' => 'required|string|confirmed|min:6',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.between' => 'Họ và tên phải có từ 2 đến 100 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được đăng ký.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Use transaction to ensure both user and customer are created
        DB::beginTransaction();

        try {
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

            // Create customer record for this user
            Customer::create([
                'customer_id' => $user->user_id,
                'user_id' => $user->user_id
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
                // Log but don't fail registration - user can verify later
                Log::error('Failed to send account verification email: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để xác nhận tài khoản.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Đăng ký thất bại. Vui lòng thử lại sau.'
            ], 500);
        }
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

    // update profile
    public function updateProfile(Request $request)
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Only update user table (not admin)
            if ($user instanceof User) {
                $validator = Validator::make($request->all(), [
                    'name' => 'sometimes|string|between:2,100',
                    'email' => 'sometimes|string|email|max:100|unique:users,user_email,' . $user->user_id . ',user_id',
                    'phone' => 'sometimes|string|max:15',
                    'address' => 'sometimes|string|max:255',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 400);
                }

                $updated = false;

                if ($request->has('name') && $request->name !== null) {
                    $user->user_name = $request->name;
                    $updated = true;
                }
                if ($request->has('email') && $request->email !== null) {
                    $user->user_email = $request->email;
                    $updated = true;
                }
                if ($request->has('phone') && $request->phone !== null) {
                    $user->user_phone = $request->phone;
                    $updated = true;
                }
                if ($request->has('address') && $request->address !== null) {
                    $user->user_address = $request->address;
                    $updated = true;
                }

                if ($updated) {
                    $user->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật thông tin thành công',
                    'user' => $this->getFormattedUser($user)
                ]);
            }

            return response()->json(['error' => 'Cannot update admin profile here'], 403);

        } catch (\Exception $e) {
            Log::error('Update profile failed: ' . $e->getMessage());
            return response()->json(['error' => 'Cập nhật thất bại: ' . $e->getMessage()], 500);
        }
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
                'phone' => $user->user_phone,
                'address' => $user->user_address ?? '',
                'login_name' => $user->user_login_name,
                'user_type' => 'user'
            ];
        }
    }
}