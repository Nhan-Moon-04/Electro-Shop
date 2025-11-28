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
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Try to get token from Authorization header first, then from cookie
            $token = null;
            
            // Debug logging
            \Log::info('AdminMiddleware - Request URI: ' . $request->getRequestUri());
            \Log::info('AdminMiddleware - Cookie admin_token: ' . $request->cookie('admin_token'));
            \Log::info('AdminMiddleware - Header Authorization: ' . $request->header('Authorization'));
            \Log::info('AdminMiddleware - Header X-Admin-Token: ' . $request->header('X-Admin-Token'));
            
            // Check Authorization header first
            if ($request->header('Authorization')) {
                $token = str_replace('Bearer ', '', $request->header('Authorization'));
                JWTAuth::setToken($token);
            } elseif ($request->header('X-Admin-Token')) {
                // Try to get from request header X-Admin-Token (set by frontend)
                $token = $request->header('X-Admin-Token');
                JWTAuth::setToken($token);
            } elseif ($request->input('_token_admin')) {
                // Try to get from hidden form field (for regular form submissions)
                $token = $request->input('_token_admin');
                JWTAuth::setToken($token);
            } elseif ($request->cookie('admin_token')) {
                // Try to get token from cookie as last resort
                $token = $request->cookie('admin_token');
                JWTAuth::setToken($token);
            }
            
            \Log::info('AdminMiddleware - Token found: ' . ($token ? 'YES' : 'NO'));
            
            if (!$token) {
                \Log::warning('AdminMiddleware - No token found, redirecting to login');
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return redirect('/admin/login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
            }

            \Log::info('AdminMiddleware - Parsing token to get payload...');
            $payload = JWTAuth::getPayload($token);
            \Log::info('AdminMiddleware - Payload user_type: ' . ($payload->get('user_type') ?? 'NOT SET'));
            
            // Check if user_type is admin
            if ($payload->get('user_type') !== 'admin') {
                \Log::warning('AdminMiddleware - User type is not admin');
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Forbidden'], 403);
                }
                return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này');
            }
            
            \Log::info('AdminMiddleware - Attempting to authenticate token...');
            $user = JWTAuth::authenticate($token);
            \Log::info('AdminMiddleware - User authenticated: ' . ($user ? get_class($user) . ' ID:' . ($user->admin_id ?? $user->user_id) : 'NULL'));
            
            if (!$user) {
                \Log::warning('AdminMiddleware - Authentication failed');
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Invalid token'], 401);
                }
                return redirect('/admin/login')->with('error', 'Token không hợp lệ');
            }

            // If user is Admin model, check active status
            if ($user instanceof Admin) {
                \Log::info('AdminMiddleware - Checking admin active status: ' . ($user->admin_active ? 'ACTIVE' : 'INACTIVE'));
                if (!$user->admin_active) {
                    \Log::warning('AdminMiddleware - Admin account is disabled');
                    if ($request->expectsJson()) {
                        return response()->json(['error' => 'Account disabled'], 403);
                    }
                    return redirect('/admin/login')->with('error', 'Tài khoản admin đã bị vô hiệu hóa');
                }
            }

            \Log::info('AdminMiddleware - All checks passed, allowing access');
            return $next($request);
        } catch (JWTException $e) {
            \Log::error('AdminMiddleware - JWT Exception: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Token expired'], 401);
            }
            return redirect('/admin/login')->with('error', 'Phiên đăng nhập đã hết hạn');
        } catch (\Exception $e) {
            \Log::error('AdminMiddleware - General Exception: ' . $e->getMessage());
            return redirect('/admin/login')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
