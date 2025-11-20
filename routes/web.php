<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Admin\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Trang chủ

Route::get('/', [HomeController::class, 'index'])->name('home');
// Sản phẩm
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/search', function () {
    return view('products.index');
})->name('products.search');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show')->where('id', '[0-9]+');

Route::get('/category/{id}', [ProductController::class, 'category'])->name('products.category')->where('id', '[0-9]+');

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Thanh toán
Route::get('/checkout', [PaymentController::class, 'showCheckout'])->name('checkout.index');

// Tài khoản
Route::middleware(['auth'])->group(function () {
    Route::get('/account/profile', function () {
        return view('account.profile');
    })->name('account.profile');

    Route::put('/account/update', function () {
        // Xử lý cập nhật profile
    })->name('account.update');

    Route::get('/account/orders', function () {
        return view('account.profile');
    })->name('account.orders');

    Route::get('/account/addresses', function () {
        return view('account.profile');
    })->name('account.addresses');

    Route::get('/account/wishlist', function () {
        return view('account.profile');
    })->name('account.wishlist');

    Route::get('/account/reviews', function () {
        return view('account.profile');
    })->name('account.reviews');

    Route::get('/account/vouchers', function () {
        return view('account.profile');
    })->name('account.vouchers');

    Route::get('/account/password', function () {
        return view('account.profile');
    })->name('account.password');
});



//  đăng nhập / đăng ký

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// trang tài khoản
Route::get('/account', function () {
    return view('account.profile');
})->name('account.profile');

// Quên mật khẩu
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request'); // Đặt tên chuẩn là 'password.request'

//  Đặt lại mật khẩu"
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

// Account email verification link
Route::get('/verify-account/{token}', [ActivationController::class, 'verify'])->name('account.verify');

// Payment routes (create order, show payment, webhook) - đã import ở trên

Route::post('/create-order', [PaymentController::class, 'createOrder'])->name('orders.create');
Route::get('/payment/{orderId}', [PaymentController::class, 'showPayment'])->name('payment.show');
// webhook (no auth) used by payment provider to notify
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// VNPay routes
Route::prefix('vnpay')->group(function () {
    Route::post('/create-payment', [VNPayController::class, 'createPayment'])->name('vnpay.create');
    Route::get('/return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::post('/ipn', [VNPayController::class, 'vnpayIPN'])->name('vnpay.ipn');
    Route::post('/generate-qr', [VNPayController::class, 'generateQR'])->name('vnpay.qr');
});

// Admin Routes - Bảo vệ bằng middleware admin
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', AdminProductController::class);

    // Product Image Delete
    Route::delete('/products/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.delete');

    // Product Restore
    Route::post('/products/{id}/restore', [AdminProductController::class, 'restore'])->name('products.restore');

    // Categories Management
    Route::resource('categories', AdminCategoryController::class);
    Route::post('/categories/{id}/restore', [AdminCategoryController::class, 'restore'])->name('categories.restore');

    // Suppliers Management
    Route::resource('suppliers', AdminSupplierController::class);
    Route::post('/suppliers/{id}/restore', [AdminSupplierController::class, 'restore'])->name('suppliers.restore');

    // Routes quản lý đơn hàng
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/address', [OrderController::class, 'updateDeliveryAddress'])->name('updateAddress');
        Route::post('/{id}/note', [OrderController::class, 'updateNote'])->name('updateNote');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [OrderController::class, 'print'])->name('print');
        Route::get('/statistics/view', [OrderController::class, 'statistics'])->name('statistics');
    });

    // Settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});







