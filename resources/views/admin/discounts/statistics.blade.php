@extends('layouts.admin')

@section('title', 'Thống kê khuyến mãi')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Thống kê khuyến mãi</h1>
            <p class="text-gray-600 mt-1">{{ $discount->discount_name }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.discounts.edit', $discount->discount_id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa
            </a>
            <a href="{{ route('admin.discounts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

{{-- Discount Info Card --}}
<div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
            <div class="text-4xl font-bold mb-2">{{ $discount->discount_amount }}%</div>
            <div class="text-blue-100">Giảm giá</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold mb-2">{{ $stats['total_orders'] }}</div>
            <div class="text-blue-100">Đơn hàng đã dùng</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold mb-2">
                @php
                    $daysRemaining = now()->diffInDays($discount->discount_end_date, false);
                @endphp
                {{ $daysRemaining >= 0 ? $daysRemaining : 0 }}
            </div>
            <div class="text-blue-100">Ngày còn lại</div>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    {{-- Total Orders --}}
    <div class="bg-white rounded-lg shadow-md p-6 text-center border-t-4 border-green-400 hover:shadow-lg transition">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-shopping-cart text-3xl text-green-600"></i>
        </div>
        <p class="text-3xl font-bold text-green-600 mb-2">{{ $stats['total_orders'] }}</p>
        <p class="text-gray-600">Đơn hàng</p>
    </div>

    {{-- Total Revenue --}}
    <div class="bg-white rounded-lg shadow-md p-6 text-center border-t-4 border-blue-400 hover:shadow-lg transition">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-money-bill-wave text-3xl text-blue-600"></i>
        </div>
        <p class="text-2xl font-bold text-blue-600 mb-2">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</p>
        <p class="text-gray-600">Doanh thu</p>
    </div>

    {{-- Status --}}
    <div class="bg-white rounded-lg shadow-md p-6 text-center border-t-4 {{ $stats['is_active'] ? 'border-green-400' : 'border-red-400' }} hover:shadow-lg transition">
        <div class="w-16 h-16 {{ $stats['is_active'] ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas {{ $stats['is_active'] ? 'fa-check-circle' : 'fa-times-circle' }} text-3xl {{ $stats['is_active'] ? 'text-green-600' : 'text-red-600' }}"></i>
        </div>
        <p class="text-xl font-bold {{ $stats['is_active'] ? 'text-green-600' : 'text-red-600' }} mb-2">
            {{ $stats['is_active'] ? 'Đang hoạt động' : 'Không hoạt động' }}
        </p>
        <p class="text-gray-600">Trạng thái</p>
    </div>
</div>

{{-- Discount Details --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Basic Info --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Thông tin cơ bản
        </h3>
        
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">ID:</span>
                <span class="font-semibold">#{{ $discount->discount_id }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Tên khuyến mãi:</span>
                <span class="font-semibold">{{ $discount->discount_name }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Phần trăm giảm:</span>
                <span class="font-bold text-red-600">{{ $discount->discount_amount }}%</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Ngày bắt đầu:</span>
                <span class="font-semibold">{{ \Carbon\Carbon::parse($discount->discount_start_date)->format('d/m/Y') }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Ngày kết thúc:</span>
                <span class="font-semibold">{{ \Carbon\Carbon::parse($discount->discount_end_date)->format('d/m/Y') }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Hiển thị:</span>
                <span>
                    @if($discount->discount_is_display)
                        <i class="fas fa-check-circle text-green-500"></i> Đang bật
                    @else
                        <i class="fas fa-times-circle text-red-500"></i> Đã tắt
                    @endif
                </span>
            </div>

            @if($discount->discount_description)
            <div class="pt-3">
                <span class="text-gray-600 block mb-2">Mô tả:</span>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $discount->discount_description }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Usage Statistics --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-pie text-green-500 mr-2"></i>
            Thống kê doanh thu
        </h3>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Tổng doanh thu:</span>
                <span class="font-bold text-green-600">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Tổng giảm giá:</span>
                <span class="font-bold text-red-600">{{ number_format($stats['total_discount_amount'], 0, ',', '.') }}₫</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Trung bình/đơn:</span>
                @php
                    $avgPerOrder = $stats['total_orders'] > 0 ? $stats['total_revenue'] / $stats['total_orders'] : 0;
                @endphp
                <span class="font-bold text-blue-600">{{ number_format($avgPerOrder, 0, ',', '.') }}₫</span>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg text-center mt-4">
                <i class="fas fa-info-circle text-blue-500 mb-2"></i>
                <p class="text-sm text-gray-700">Khuyến mãi này đã được áp dụng cho <strong>{{ $stats['total_orders'] }}</strong> đơn hàng</p>
            </div>
        </div>
    </div>
</div>

{{-- Orders List --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-shopping-bag text-purple-500 mr-2"></i>
            Đơn hàng áp dụng khuyến mãi này
        </h3>
    </div>

    @if($discount->orders && $discount->orders->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Mã đơn</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Khách hàng</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Ngày đặt</th>
                    <th class="text-right py-4 px-6 text-sm font-semibold text-gray-700">Tổng tiền</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Trạng thái</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($discount->orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-6">
                        <a href="{{ route('admin.orders.show', $order->order_id) }}" class="text-primary font-semibold hover:underline">
                            #{{ $order->order_id }}
                        </a>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-medium text-gray-800">{{ $order->customer->customer_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->order_phone ?? 'N/A' }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <p class="font-bold text-gray-800">{{ number_format($order->order_total_after ?? 0, 0, ',', '.') }}₫</p>
                        @if($order->order_total_before != $order->order_total_after)
                        <p class="text-xs text-gray-500 line-through">{{ number_format($order->order_total_before ?? 0, 0, ',', '.') }}₫</p>
                        <p class="text-xs text-red-500">-{{ number_format($order->order_total_before - $order->order_total_after, 0, ',', '.') }}₫</p>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        @php
                            $statusConfig = [
                                'Chờ thanh toán' => ['color' => 'yellow', 'icon' => 'fa-clock'],
                                'Đang giao hàng' => ['color' => 'blue', 'icon' => 'fa-shipping-fast'],
                                'Hoàn thành' => ['color' => 'green', 'icon' => 'fa-check-circle'],
                                'Đã hủy' => ['color' => 'red', 'icon' => 'fa-times-circle'],
                            ];
                            $status = $statusConfig[$order->order_status] ?? ['color' => 'gray', 'icon' => 'fa-question'];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                            <i class="fas {{ $status['icon'] }} mr-1"></i>
                            {{ $order->order_status }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                           class="text-blue-600 hover:text-blue-800 transition" 
                           title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="py-12 text-center">
        <div class="text-gray-400">
            <i class="fas fa-inbox text-6xl mb-4"></i>
            <p class="text-lg">Chưa có đơn hàng nào sử dụng khuyến mãi này</p>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Auto refresh every 30 seconds if active
@if($stats['is_active'])
setTimeout(function() {
    location.reload();
}, 30000);
@endif
</script>
@endpush
