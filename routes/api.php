<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Import các controller chúng ta sẽ tạo ở bước sau
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

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
        Route::put('/update-profile', [AuthController::class, 'updateProfile']); // Cập nhật thông tin user
        Route::post('/refresh', [AuthController::class, 'refresh']); // Làm mới token
    });
});

// Cart Routes
Route::group([
    'prefix' => 'cart',
    'middleware' => 'auth:api'
], function () {
    Route::get('/count', [CartController::class, 'getCartCount']);
    Route::get('/', [CartController::class, 'getCart']);
    Route::post('/add', [CartController::class, 'addToCart']);
    Route::put('/update', [CartController::class, 'updateQuantity']);
    Route::delete('/remove', [CartController::class, 'removeItem']);
    Route::post('/checkout', [CartController::class, 'checkout']);
});

// Product Routes
Route::get('/products/{id}', [ProductController::class, 'show']);

// Order Routes
Route::group([
    'prefix' => 'orders',
    'middleware' => 'auth:api'
], function () {
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/{id}/cancel', [OrderController::class, 'cancel']);
});

