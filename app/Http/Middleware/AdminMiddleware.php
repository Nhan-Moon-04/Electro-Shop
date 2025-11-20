<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use App\Models\Admin;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return redirect('/login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
            }

            $user = JWTAuth::authenticate($token);
            if (!$user) {
                return redirect('/login')->with('error', 'Token không hợp lệ');
            }

            // Kiểm tra xem user có phải admin không
            if (!($user instanceof Admin)) {
                return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này');
            }

            // Kiểm tra admin có active không
            if (!$user->admin_active) {
                return redirect('/login')->with('error', 'Tài khoản admin đã bị vô hiệu hóa');
            }

            return $next($request);
        } catch (JWTException $e) {
            return redirect('/login')->with('error', 'Phiên đăng nhập đã hết hạn');
        }
    }
}
