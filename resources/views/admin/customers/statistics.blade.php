@extends('layouts.admin')

@section('title', 'Thống kê khách hàng')

@section('content')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Thống kê khách hàng</h1>
            <p class="text-gray-600 mt-1">Phân tích hoạt động và chi tiêu của khách hàng</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
    </div>
</div>

{{-- Overall Statistics --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Tổng khách hàng</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_customers'] }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-users text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Đang hoạt động</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['active_customers'] }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-user-check text-2xl text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Không hoạt động</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['inactive_customers'] }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <i class="fas fa-user-slash text-2xl text-red-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Mới tháng này</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['new_this_month'] }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-user-plus text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
</div>

{{-- Top Customers --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- By Spending --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-star text-yellow-500 mr-2"></i>Top khách hàng chi tiêu nhiều
        </h3>

        <div class="space-y-3">
            @forelse($topCustomers as $index => $customer)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 {{ $index < 3 ? 'bg-yellow-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center font-bold {{ $index < 3 ? 'text-yellow-600' : 'text-gray-600' }}">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $customer->user->user_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->user->user_email ?? '' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-green-600">{{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}₫</p>
                    <a href="{{ route('admin.customers.show', $customer->customer_id) }}" 
                       class="text-xs text-blue-600 hover:underline">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-400 py-8">Chưa có dữ liệu</p>
            @endforelse
        </div>
    </div>

    {{-- By Order Count --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-shopping-cart text-blue-500 mr-2"></i>Top khách hàng mua nhiều đơn
        </h3>

        <div class="space-y-3">
            @forelse($customersByOrders as $index => $customer)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 {{ $index < 3 ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center font-bold {{ $index < 3 ? 'text-blue-600' : 'text-gray-600' }}">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $customer->user->user_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->user->user_email ?? '' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-blue-600">{{ $customer->order_count }} đơn</p>
                    <a href="{{ route('admin.customers.show', $customer->customer_id) }}" 
                       class="text-xs text-blue-600 hover:underline">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-400 py-8">Chưa có dữ liệu</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
