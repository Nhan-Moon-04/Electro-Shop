<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ActivationController;

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
Route::get('/cart', function () {
    return view('cart.index');
})->name('cart.index');

// Thanh toán
Route::get('/checkout', function () {
    return view('checkout.index');
})->name('checkout.index');

Route::post('/checkout/process', function () {
    // Xử lý thanh toán
})->name('checkout.process');

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








