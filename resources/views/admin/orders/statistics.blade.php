@extends('layouts.admin')

@section('title', 'Thống kê đơn hàng')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Thống kê đơn hàng</h1>
            <p class="text-gray-600 mt-1">Báo cáo và phân tích đơn hàng</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

{{-- Date Filter --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.orders.statistics') }}" class="flex items-end gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
            <input type="date" name="start_date" value="{{ $startDate }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
            <input type="date" name="end_date" value="{{ $endDate }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
            <i class="fas fa-search mr-2"></i>Lọc
        </button>
    </form>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-6">
    {{-- Total Orders --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-2">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-cart text-2xl text-blue-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['total_orders']) }}</p>
        <p class="text-sm text-gray-600">Tổng đơn hàng</p>
    </div>

    {{-- Total Revenue --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-2">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-dollar-sign text-2xl text-green-500"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800 mb-1">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</p>
        <p class="text-sm text-gray-600">Tổng doanh thu</p>
    </div>

    {{-- Pending Orders --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-2">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-2xl text-yellow-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['pending_orders']) }}</p>
        <p class="text-sm text-gray-600">Chờ thanh toán</p>
    </div>

    {{-- Shipping Orders --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-2">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shipping-fast text-2xl text-purple-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['shipping_orders']) }}</p>
        <p class="text-sm text-gray-600">Đang giao hàng</p>
    </div>

    {{-- Completed Orders --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-teal-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-2">
            <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-2xl text-teal-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['completed_orders']) }}</p>
        <p class="text-sm text-gray-600">Hoàn thành</p>
    </div>

    {{-- Cancelled Orders --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-2">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-times-circle text-2xl text-red-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['cancelled_orders']) }}</p>
        <p class="text-sm text-gray-600">Đã hủy</p>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Order Status Chart --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
            Phân bố trạng thái đơn hàng
        </h3>
        <div style="position: relative; height: 300px;">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>

    {{-- Revenue Summary --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
            Chi tiết doanh thu
        </h3>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Tổng doanh thu:</span>
                <span class="font-bold text-green-600 text-xl">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Trung bình/đơn:</span>
                @php
                    $avgPerOrder = $stats['total_orders'] > 0 ? $stats['total_revenue'] / $stats['total_orders'] : 0;
                @endphp
                <span class="font-bold text-blue-600">{{ number_format($avgPerOrder, 0, ',', '.') }}₫</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Tỷ lệ hoàn thành:</span>
                @php
                    $completionRate = $stats['total_orders'] > 0 ? ($stats['completed_orders'] / $stats['total_orders']) * 100 : 0;
                @endphp
                <span class="font-bold text-teal-600">{{ number_format($completionRate, 1) }}%</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Tỷ lệ hủy:</span>
                @php
                    $cancellationRate = $stats['total_orders'] > 0 ? ($stats['cancelled_orders'] / $stats['total_orders']) * 100 : 0;
                @endphp
                <span class="font-bold text-red-600">{{ number_format($cancellationRate, 1) }}%</span>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-lg mt-4">
                <p class="text-sm text-gray-700 text-center">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    Dữ liệu từ <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> 
                    đến <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Order Status Pie Chart
    const ctx = document.getElementById('orderStatusChart');
    
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Chờ thanh toán', 'Đang giao hàng', 'Hoàn thành', 'Đã hủy'],
                datasets: [{
                    data: [
                        {{ $stats['pending_orders'] }},
                        {{ $stats['shipping_orders'] }},
                        {{ $stats['completed_orders'] }},
                        {{ $stats['cancelled_orders'] }}
                    ],
                    backgroundColor: [
                        'rgb(234, 179, 8)',   // Yellow
                        'rgb(168, 85, 247)',  // Purple
                        'rgb(20, 184, 166)',  // Teal
                        'rgb(239, 68, 68)'    // Red
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
