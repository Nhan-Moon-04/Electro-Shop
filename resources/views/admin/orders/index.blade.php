@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Quản lý đơn hàng</h1>
            <p class="text-gray-600 mt-1">Quản lý và theo dõi tất cả đơn hàng</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.statistics') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-chart-bar mr-2"></i>Thống kê
            </a>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Search --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Mã đơn, tên khách hàng, SĐT..." 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        {{-- Status Filter --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Tất cả</option>
                <option value="Chờ thanh toán" {{ request('status') == 'Chờ thanh toán' ? 'selected' : '' }}>Chờ thanh toán</option>
                <option value="Đang giao hàng" {{ request('status') == 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                <option value="Hoàn thành" {{ request('status') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="Đã hủy" {{ request('status') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        {{-- Date From --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        {{-- Date To --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        {{-- Buttons --}}
        <div class="md:col-span-4 flex items-center space-x-3">
            <button type="submit" class="bg-primary hover:bg-primary-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-search mr-2"></i>Tìm kiếm
            </button>
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-redo mr-2"></i>Đặt lại
            </a>
        </div>
    </form>
</div>

{{-- Status Summary --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-md p-4 text-center border-t-4 border-yellow-400 hover:shadow-lg transition">
        <a href="{{ route('admin.orders.index', ['status' => 'Chờ thanh toán']) }}" class="block">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-clock text-2xl text-yellow-600"></i>
            </div>
            <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\Order::where('order_status', 'Chờ thanh toán')->count() }}</p>
            <p class="text-sm text-gray-600 mt-1">Chờ thanh toán</p>
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-4 text-center border-t-4 border-blue-400 hover:shadow-lg transition">
        <a href="{{ route('admin.orders.index', ['status' => 'Đang giao hàng']) }}" class="block">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-shipping-fast text-2xl text-blue-600"></i>
            </div>
            <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Order::where('order_status', 'Đang giao hàng')->count() }}</p>
            <p class="text-sm text-gray-600 mt-1">Đang giao hàng</p>
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-4 text-center border-t-4 border-green-400 hover:shadow-lg transition">
        <a href="{{ route('admin.orders.index', ['status' => 'Hoàn thành']) }}" class="block">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-check-circle text-2xl text-green-600"></i>
            </div>
            <p class="text-2xl font-bold text-green-600">{{ \App\Models\Order::where('order_status', 'Hoàn thành')->count() }}</p>
            <p class="text-sm text-gray-600 mt-1">Hoàn thành</p>
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-4 text-center border-t-4 border-red-400 hover:shadow-lg transition">
        <a href="{{ route('admin.orders.index', ['status' => 'Đã hủy']) }}" class="block">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-times-circle text-2xl text-red-600"></i>
            </div>
            <p class="text-2xl font-bold text-red-600">{{ \App\Models\Order::where('order_status', 'Đã hủy')->count() }}</p>
            <p class="text-sm text-gray-600 mt-1">Đã hủy</p>
        </a>
    </div>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Mã đơn</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Khách hàng</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Ngày đặt</th>
                    <th class="text-right py-4 px-6 text-sm font-semibold text-gray-700">Tổng tiền</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Thanh toán</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Trạng thái</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
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
                        <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->order_date)->format('H:i') }}</p>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <p class="font-bold text-gray-800">{{ number_format($order->order_total_after ?? 0, 0, ',', '.') }}₫</p>
                        @if($order->order_total_before != $order->order_total_after)
                        <p class="text-xs text-gray-500 line-through">{{ number_format($order->order_total_before ?? 0, 0, ',', '.') }}₫</p>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $order->order_paying_status == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fas {{ $order->order_paying_status == 1 ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                            {{ $order->order_paying_status == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
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
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                               class="text-blue-600 hover:text-blue-800 transition" 
                               title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.print', $order->order_id) }}" 
                               class="text-green-600 hover:text-green-800 transition" 
                               title="In đơn hàng"
                               target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                            @if($order->order_status == 'Đã hủy')
                            <form action="{{ route('admin.orders.destroy', $order->order_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-inbox text-6xl mb-4"></i>
                            <p class="text-lg">Không tìm thấy đơn hàng nào</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    // Auto-refresh every 60 seconds for pending orders
    setTimeout(function() {
        if(window.location.search.includes('status=Chờ thanh toán')) {
            location.reload();
        }
    }, 60000);
</script>
@endpush