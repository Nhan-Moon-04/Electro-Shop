<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PasswordResetToken;
use App\Mail\PasswordResetTokenMail;
use Illuminate\Support\Facades\Mail;
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


        // Tạo token tùy chỉnh và lưu vào bảng password_reset_tokens
        $user = User::where('user_email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Không tìm thấy tài khoản.'], 404);
        }

        // Tạo token ngẫu nhiên đủ mạnh
        $token = bin2hex(random_bytes(32)); // 64 chars
        $expireAt = now()->addMinutes(60); // 60 phút

        PasswordResetToken::create([
            'MaNguoiDung' => $user->user_id,
            'Token' => $token,
            'ExpireAt' => $expireAt,
            'Used' => false,
        ]);

        // Gửi mail bằng Mailable
        try {
            Mail::to($user->user_email)->send(new PasswordResetTokenMail($user->user_email, $token));
            Log::info('Password reset token sent to ' . $request->email);
            return response()->json(['message' => 'Link reset mật khẩu đã được gửi! Vui lòng kiểm tra hộp thư của bạn (cả thư mục Spam).'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset token email: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể gửi link reset. Vui lòng thử lại sau.'], 500);
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


        // Tìm token trong bảng của chúng ta
        $record = PasswordResetToken::where('Token', $request->token)->first();

        if (!$record) {
            return response()->json(['error' => 'Token không hợp lệ.'], 400);
        }

        if ($record->Used) {
            return response()->json(['error' => 'Token đã được sử dụng.'], 400);
        }

        if (now()->greaterThan($record->ExpireAt)) {
            return response()->json(['error' => 'Token đã hết hạn.'], 400);
        }

        $user = $record->user;
        if (!$user || $user->user_email !== $request->email) {
            return response()->json(['error' => 'Email không khớp với token.'], 400);
        }

        // Cập nhật mật khẩu
        $user->user_password = Hash::make($request->password);
        $user->save();

        // Đánh dấu token đã dùng
        $record->Used = true;
        $record->save();

        return response()->json(['message' => 'Mật khẩu đã được reset thành công!'], 200);
    }
}