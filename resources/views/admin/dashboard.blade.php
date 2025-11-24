@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Tổng sản phẩm --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tổng sản phẩm</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalProducts) }}</h3>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> 12% so với tháng trước
                    </p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-2xl text-blue-500"></i>
                </div>
            </div>
        </div>

        {{-- Đơn hàng --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Đơn hàng</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalOrders) }}</h3>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> 8% so với tháng trước
                    </p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-2xl text-green-500"></i>
                </div>
            </div>
        </div>

        {{-- Khách hàng --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Khách hàng</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalUsers) }}</h3>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> 15% so với tháng trước
                    </p>
                </div>
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-purple-500"></i>
                </div>
            </div>
        </div>

        {{-- Doanh thu --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Doanh thu</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($currentMonthRevenue) }}đ</h3>
                    <p class="text-xs {{ $revenuePercentChange >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class="fas fa-arrow-{{ $revenuePercentChange >= 0 ? 'up' : 'down' }}"></i> 
                        {{ number_format(abs($revenuePercentChange), 1) }}% so với tháng trước
                    </p>
                </div>
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl text-yellow-500"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Biểu đồ doanh thu theo tháng --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Doanh thu theo tháng</h2>
                <select id="yearSelect" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div style="position: relative; height: 350px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Sản phẩm theo danh mục --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Sản phẩm theo danh mục</h2>
            <div class="space-y-3">
                @foreach($categoryStats as $category)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center">
                                <i class="fas fa-tag text-blue-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">{{ $category->category_name }}</span>
                            </div>
                            <span class="text-sm text-gray-600">{{ $category->percentage }}%</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-2">{{ $category->products_count }} sản phẩm</div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $category->percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top sản phẩm bán chạy --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Sản phẩm bán chạy</h2>
                <a href="{{ route('admin.products.index') }}" class="text-primary hover:text-primary-600 text-sm font-medium">
                    Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($topProducts as $product)
                <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition">
                    <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                        @if($product->product_avt_img && $product->product_avt_img != 'default.png')
                            <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}" 
                                 alt="{{ $product->product_name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.src='{{ asset('imgs/default.png') }}'">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $product->product_name }}</p>
                      
                        <p class="text-sm text-gray-500">{{ $product->category_name ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-primary">
                            @if($product->min_price)
                                {{ number_format($product->min_price, 0, ',', '.') }}₫
                            @else
                                Liên hệ
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-shopping-cart text-xs mr-1"></i>
                            Đã bán: {{ number_format($product->total_sold ?? 0) }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-box-open text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">Chưa có sản phẩm nào được bán</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Đơn hàng gần đây --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Đơn hàng gần đây</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Mã đơn</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Khách hàng</th>
                            <th class="text-right py-2 text-sm font-semibold text-gray-700">Tổng tiền</th>
                            <th class="text-center py-2 text-sm font-semibold text-gray-700">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 text-sm">#{{ $order->order_id }}</td>
                                <td class="py-3 text-sm">{{ $order->order_name }}</td>
                                <td class="py-3 text-sm text-right font-semibold">{{ number_format($order->order_total_after) }}đ</td>
                                <td class="py-3 text-center">
                                    @if($order->order_is_paid)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Đã thanh toán</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Chưa thanh toán</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Khởi tạo biểu đồ
let revenueChart;

function initChart(revenueData, ordersData) {
    const ctx = document.getElementById('revenueChart');
    
    // Destroy chart cũ nếu có
    if (revenueChart) {
        revenueChart.destroy();
    }
    
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: revenueData,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // QUAN TRỌNG: Không giữ tỷ lệ khung hình
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + 'đ';
                        },
                        afterLabel: function(context) {
                            return 'Đơn hàng: ' + ordersData[context.dataIndex];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'K';
                            }
                            return value;
                        }
                    },
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Khởi tạo biểu đồ với data từ server
const initialRevenueData = @json($monthlyRevenueData);
const initialOrdersData = @json($monthlyOrdersData);

// Đợi DOM load xong mới init chart
document.addEventListener('DOMContentLoaded', function() {
    initChart(initialRevenueData, initialOrdersData);
});

// Xử lý thay đổi năm
document.getElementById('yearSelect').addEventListener('change', function() {
    const year = this.value;
    
    // Hiển thị loading
    const chartContainer = document.getElementById('revenueChart').parentElement;
    chartContainer.style.opacity = '0.5';
    
    // Gọi API để lấy dữ liệu mới
    fetch(`{{ route('admin.revenue.data') }}?year=${year}`)
        .then(response => response.json())
        .then(data => {
            // Cập nhật biểu đồ với dữ liệu mới
            initChart(data.revenue, data.orders);
            chartContainer.style.opacity = '1';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Không thể tải dữ liệu. Vui lòng thử lại!');
            chartContainer.style.opacity = '1';
        });
});
</script>
@endpush
