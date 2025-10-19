<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{


    public function sendResetLinkEmail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,user_email',
        ]);

        if ($validator->fails()) {

            if ($validator->errors()->has('email') && str_contains($validator->errors()->first('email'), 'exists')) {
                return response()->json(['error' => 'Không tìm thấy tài khoản nào với email này.'], 404);
            }
            return response()->json($validator->errors(), 422);
        }


        $credentials = ['user_email' => $request->email];
        $status = Password::broker()->sendResetLink($credentials);

        // Ghi log trạng thái gửi mail
        Log::info('Password reset status for ' . $request->email . ': ' . $status);

        if ($status == Password::RESET_LINK_SENT) {

            return response()->json(['message' => 'Link reset mật khẩu đã được gửi! Vui lòng kiểm tra hộp thư của bạn (cả thư mục Spam).'], 200);
        } else {

            Log::error('Failed to send password reset link for ' . $request->email . '. Status: ' . $status);
            return response()->json([
                'error' => 'Không thể gửi link reset.',
                'status' => $status
            ], 400);
        }
    }


    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $credentials = [
            'user_email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'token' => $request->token
        ];


        $status = Password::broker()->reset(
            $credentials,
            function ($user, $password) {

                $user->user_password = Hash::make($password);
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Mật khẩu đã được reset thành công!'], 200);
        } else {

            Log::error('Failed to reset password for ' . $request->email . '. Status: ' . $status);
            return response()->json([
                'error' => 'Token không hợp lệ hoặc đã hết hạn.',
                'status' => $status
            ], 400);
        }
    }
}