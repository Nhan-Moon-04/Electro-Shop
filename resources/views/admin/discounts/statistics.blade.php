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
            <div class="text-4xl font-bold mb-2">{{ $stats['total_products'] }}</div>
            <div class="text-blue-100">Sản phẩm áp dụng</div>
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
    {{-- Total Products --}}
    <div class="bg-white rounded-lg shadow-md p-6 text-center border-t-4 border-green-400 hover:shadow-lg transition">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-box text-3xl text-green-600"></i>
        </div>
        <p class="text-3xl font-bold text-green-600 mb-2">{{ $stats['total_products'] }}</p>
        <p class="text-gray-600">Sản phẩm</p>
    </div>

    {{-- Products With Stock --}}
    <div class="bg-white rounded-lg shadow-md p-6 text-center border-t-4 border-blue-400 hover:shadow-lg transition">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-warehouse text-3xl text-blue-600"></i>
        </div>
        <p class="text-2xl font-bold text-blue-600 mb-2">{{ $stats['products_with_stock'] }}</p>
        <p class="text-gray-600">Còn hàng</p>
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
                <span class="text-gray-600">Tổng sản phẩm:</span>
                <span class="font-bold text-green-600">{{ $stats['total_products'] }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Sản phẩm còn hàng:</span>
                <span class="font-bold text-blue-600">{{ $stats['products_with_stock'] }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Phần trăm giảm:</span>
                <span class="font-bold text-red-600">{{ $stats['discount_percentage'] }}%</span>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg text-center mt-4">
                <i class="fas fa-info-circle text-blue-500 mb-2"></i>
                <p class="text-sm text-gray-700">Khuyến mãi này đã được áp dụng cho <strong>{{ $stats['total_products'] }}</strong> sản phẩm</p>
            </div>
        </div>
    </div>
</div>

{{-- Products List --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-box text-purple-500 mr-2"></i>
            Sản phẩm áp dụng khuyến mãi này
        </h3>
    </div>

    @if($discount->variants && $discount->variants->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Tên sản phẩm</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Màu sắc</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Số lượng</th>
                    <th class="text-right py-4 px-6 text-sm font-semibold text-gray-700">Giá gốc</th>
                    <th class="text-right py-4 px-6 text-sm font-semibold text-gray-700">Giá sau giảm</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($discount->variants as $variant)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-6">
                        <p class="font-medium text-gray-800">{{ $variant->product->product_name ?? 'N/A' }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-block px-3 py-1 bg-gray-100 rounded-full text-sm">
                            {{ $variant->variant_color ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="font-semibold {{ $variant->variant_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $variant->variant_quantity }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <p class="text-gray-600 line-through">{{ number_format($variant->variant_price ?? 0, 0, ',', '.') }}₫</p>
                    </td>
                    <td class="py-4 px-6 text-right">
                        @php
                            $discountedPrice = $variant->variant_price * (1 - $discount->discount_amount / 100);
                        @endphp
                        <p class="font-bold text-red-600">{{ number_format($discountedPrice, 0, ',', '.') }}₫</p>
                        <p class="text-xs text-red-500">-{{ number_format($variant->variant_price - $discountedPrice, 0, ',', '.') }}₫</p>
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
            <p class="text-lg">Chưa có sản phẩm nào sử dụng khuyến mãi này</p>
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
