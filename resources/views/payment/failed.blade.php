@extends('layouts.app')

@section('title', 'Thanh toán thất bại - ElectroShop')

@section('content')

    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-8">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
            <!-- Error Icon -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times text-red-500 text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Thanh toán thất bại!</h1>
            </div>

            <!-- Error Message -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="text-red-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-medium">{{ $message ?? 'Có lỗi xảy ra trong quá trình thanh toán.' }}</span>
                </div>
            </div>

            <!-- Common Reasons -->
            <div class="text-left bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-800 mb-3">Có thể do các lý do sau:</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-2"></i>
                        <span>Số dư tài khoản không đủ</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-2"></i>
                        <span>Thông tin thẻ không chính xác</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-2"></i>
                        <span>Giao dịch bị từ chối bởi ngân hàng</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-2"></i>
                        <span>Phiên thanh toán đã hết hạn</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-2"></i>
                        <span>Lỗi kết nối mạng</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="history.back()"
                    class="block w-full bg-primary hover:bg-primary-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-redo mr-2"></i>Thử lại thanh toán
                </button>

                <a href="/cart"
                    class="block w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-shopping-cart mr-2"></i>Quay lại giỏ hàng
                </a>

                <a href="/"
                    class="block w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-home mr-2"></i>Về trang chủ
                </a>
            </div>

            <!-- Alternative Payment Methods -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-3">Phương thức thanh toán khác</h4>
                <div class="grid grid-cols-3 gap-3 text-xs">
                    <div class="bg-blue-50 p-2 rounded text-center">
                        <i class="fas fa-money-bill-wave text-blue-600 mb-1"></i>
                        <p class="text-blue-700 font-medium">COD</p>
                    </div>
                    <div class="bg-green-50 p-2 rounded text-center">
                        <i class="fas fa-qrcode text-green-600 mb-1"></i>
                        <p class="text-green-700 font-medium">QR Code</p>
                    </div>
                    <div class="bg-purple-50 p-2 rounded text-center">
                        <i class="fas fa-university text-purple-600 mb-1"></i>
                        <p class="text-purple-700 font-medium">Chuyển khoản</p>
                    </div>
                </div>
            </div>

            <!-- Support Info -->
            <div class="mt-6 text-sm text-gray-500">
                <p class="mb-2">Cần hỗ trợ? Liên hệ với chúng tôi:</p>
                <p>
                    <i class="fas fa-phone mr-1"></i>
                    Hotline: <strong class="text-gray-700">1900 1234</strong>
                </p>
                <p>
                    <i class="fas fa-envelope mr-1"></i>
                    Email: <strong class="text-gray-700">support@electroshop.vn</strong>
                </p>
            </div>
        </div>
    </div>

@endsection