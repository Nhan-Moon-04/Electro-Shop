@extends('layouts.app')

@section('title', 'Thanh toán thành công - ElectroShop')

@section('content')

    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-8">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-500 text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Thanh toán thành công!</h1>
            </div>

            <!-- Order Info -->
            @if(isset($order))
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="font-semibold text-gray-800 mb-3">Thông tin đơn hàng</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mã đơn hàng:</span>
                            <span class="font-medium">#{{ $order->order_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Người nhận:</span>
                            <span class="font-medium">{{ $order->order_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Số điện thoại:</span>
                            <span class="font-medium">{{ $order->order_phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tổng tiền:</span>
                            <span class="font-bold text-primary">{{ number_format($order->order_total_after ?? 0) }}₫</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Trạng thái:</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                {{ $order->order_status }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            <div class="text-gray-600 mb-8">
                <p class="mb-2">{{ $message ?? 'Cảm ơn bạn đã mua hàng tại ElectroShop!' }}</p>
                <p class="text-sm">Đơn hàng của bạn đang được xử lý và sẽ được giao sớm nhất có thể.</p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="/"
                    class="block w-full bg-primary hover:bg-primary-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-home mr-2"></i>Về trang chủ
                </a>

                @if(isset($order))
                    <a href="/account/orders"
                        class="block w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                        <i class="fas fa-list mr-2"></i>Xem đơn hàng
                    </a>
                @endif

                <a href="/products"
                    class="block w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-shopping-bag mr-2"></i>Tiếp tục mua sắm
                </a>
            </div>

            <!-- Additional Info -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <p class="mb-2">
                        <i class="fas fa-phone mr-1"></i>
                        Hotline hỗ trợ: <strong>1900 1234</strong>
                    </p>
                    <p>
                        <i class="fas fa-envelope mr-1"></i>
                        Email: <strong>support@electroshop.vn</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Auto redirect after 30 seconds
        setTimeout(function () {
            if (confirm('Bạn có muốn về trang chủ không?')) {
                window.location.href = '/';
            }
        }, 30000);
    </script>
@endpush