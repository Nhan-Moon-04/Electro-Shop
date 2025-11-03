@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600 mt-1">Tổng quan hệ thống ElectroShop</p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Total Products --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng sản phẩm</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalProducts) }}</h3>
                <p class="text-sm text-green-600 mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>12% so với tháng trước
                </p>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-box text-3xl text-blue-500"></i>
            </div>
        </div>
    </div>
    
    {{-- Total Orders --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Đơn hàng</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalOrders) }}</h3>
                <p class="text-sm text-green-600 mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>8% so với tháng trước
                </p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-cart text-3xl text-green-500"></i>
            </div>
        </div>
    </div>
    
    {{-- Total Users --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Khách hàng</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalUsers) }}</h3>
                <p class="text-sm text-green-600 mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>15% so với tháng trước
                </p>
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-3xl text-purple-500"></i>
            </div>
        </div>
    </div>
    
    {{-- Total Revenue --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Doanh thu</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalRevenue) }}₫</h3>
                <p class="text-sm text-green-600 mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>20% so với tháng trước
                </p>
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-dollar-sign text-3xl text-yellow-500"></i>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Revenue Chart --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Doanh thu theo tháng</h2>
            <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option>2025</option>
                <option>2024</option>
            </select>
        </div>
        <div class="h-64 flex items-center justify-center text-gray-400">
            <div class="text-center">
                <i class="fas fa-chart-line text-6xl mb-4"></i>
                <p>Biểu đồ doanh thu (Chart.js)</p>
            </div>
        </div>
    </div>
    
    {{-- Category Stats --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Sản phẩm theo danh mục</h2>
        <div class="space-y-4">
            @foreach($categoryStats as $category)
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tag text-primary"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $category->category_name }}</p>
                        <p class="text-sm text-gray-500">{{ $category->products_count }} sản phẩm</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: {{ min(($category->products_count / $totalProducts) * 100, 100) }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600">{{ number_format(($category->products_count / $totalProducts) * 100, 1) }}%</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tables Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Top Products --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Sản phẩm bán chạy</h2>
            <a href="{{ route('admin.products.index') }}" class="text-primary hover:text-primary-600 text-sm font-medium">
                Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-4">
            @foreach($topProducts as $product)
            <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition">
                <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                    @if($product->product_avt_img)
                        <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}" 
                             alt="{{ $product->product_name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.src='{{ asset('imgs/default.png') }}'">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 truncate">{{ $product->product_name }}</p>
                    <p class="text-sm text-gray-500">{{ $product->category->category_name ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-primary">
                        @if($product->min_price)
                            {{ number_format($product->min_price, 0, ',', '.') }}₫
                        @else
                            Liên hệ
                        @endif
                    </p>
                    <p class="text-sm text-gray-500">{{ number_format($product->product_view_count) }} lượt xem</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    {{-- Recent Orders --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Đơn hàng mới nhất</h2>
            {{-- <a href="{{ route('admin.orders.index') }}" class="text-primary hover:text-primary-600 text-sm font-medium">
                Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
            </a> --}}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">Mã đơn</th>
                        <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">Khách hàng</th>
                        <th class="text-right py-3 px-2 text-sm font-semibold text-gray-600">Tổng tiền</th>
                        <th class="text-center py-3 px-2 text-sm font-semibold text-gray-600">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-3 px-2">
                            <span class="text-primary font-medium">
                                #{{ $order->order_id }}
                            </span>
                        </td>
                        <td class="py-3 px-2">
                            <p class="text-sm text-gray-800">{{ $order->user->name ?? 'Guest' }}</p>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <p class="font-semibold text-gray-800">{{ number_format($order->order_total ?? 0, 0, ',', '.') }}₫</p>
                        </td>
                        <td class="py-3 px-2 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabels = [
                                    'pending' => 'Chờ xử lý',
                                    'processing' => 'Đang xử lý',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->order_status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->order_status ?? 'pending'] ?? 'Không xác định' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Chưa có đơn hàng nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Có thể thêm Chart.js hoặc ApexCharts để vẽ biểu đồ
    console.log('Admin Dashboard loaded');
</script>
@endpush
