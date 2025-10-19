<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Import các controller chúng ta sẽ tạo ở bước sau
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'prefix' => 'auth'
], function ($router) {

    // === Các Route công khai (Không cần đăng nhập) ===
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Quên mật khẩu
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

    // === Các Route riêng tư (Yêu cầu phải gửi kèm Token JWT) ===
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'userProfile']); // Lấy thông tin user
        Route::post('/refresh', [AuthController::class, 'refresh']); // Làm mới token
    });
});


