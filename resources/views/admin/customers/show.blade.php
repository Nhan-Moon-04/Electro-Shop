@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng')

@section('content')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Chi tiết khách hàng #{{ $customer->customer_id }}</h1>
            <p class="text-gray-600 mt-1">Thông tin chi tiết và lịch sử mua hàng</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.customers.edit', $customer->customer_id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa
            </a>
            <a href="{{ route('admin.customers.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Tổng đơn hàng</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-shopping-cart text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Tổng chi tiêu</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_spent'], 0, ',', '.') }}₫</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Đang xử lý</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-clock text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Hoàn thành</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['completed_orders'] }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-check-circle text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
</div>

{{-- Customer Info --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-user text-blue-500 mr-2"></i>Thông tin cá nhân
        </h3>
        
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Tên:</span>
                <span class="font-semibold">{{ $customer->user->user_name ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Email:</span>
                <span class="font-semibold">{{ $customer->user->user_email ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Số điện thoại:</span>
                <span class="font-semibold">{{ $customer->user->user_phone ?? 'Chưa có' }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b">
                <span class="text-gray-600">Trạng thái:</span>
                @if($customer->user)
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $customer->user->user_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $customer->user->user_active ? 'Hoạt động' : 'Không hoạt động' }}
                </span>
                @endif
            </div>

            <div class="pt-2">
                <span class="text-gray-600 block mb-2">Địa chỉ:</span>
                <p class="text-gray-800">{{ $customer->user->user_address ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-history text-purple-500 mr-2"></i>Đơn hàng gần đây
        </h3>

        @if($recentOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Mã đơn</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Ngày đặt</th>
                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Tổng tiền</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                               class="text-blue-600 hover:underline font-semibold">
                                #{{ $order->order_id }}
                            </a>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3 px-4 text-right font-semibold">
                            {{ number_format($order->order_total_after, 0, ',', '.') }}₫
                        </td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $statusColors = [
                                    'Chờ thanh toán' => 'yellow',
                                    'Đang giao hàng' => 'blue',
                                    'Hoàn thành' => 'green',
                                    'Đã hủy' => 'red',
                                ];
                                $color = $statusColors[$order->order_status] ?? 'gray';
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800">
                                {{ $order->order_status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-12 text-center text-gray-400">
            <i class="fas fa-inbox text-6xl mb-4"></i>
            <p>Chưa có đơn hàng nào</p>
        </div>
        @endif
    </div>
</div>

@endsection
