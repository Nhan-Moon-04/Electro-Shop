@extends('layouts.admin')

@section('title', 'Quản lý khách hàng')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Quản lý khách hàng</h1>
            <p class="text-gray-600 mt-1">Quản lý thông tin và hoạt động của khách hàng</p>
        </div>
        <a href="{{ route('admin.customers.statistics') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition">
            <i class="fas fa-chart-bar mr-2"></i>Thống kê
        </a>
    </div>
</div>

{{-- Success/Error Messages --}}
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Tổng khách hàng</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded-full">
                <i class="fas fa-users text-3xl text-blue-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Đang hoạt động</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-full">
                <i class="fas fa-user-check text-3xl text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Không hoạt động</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['inactive'] }}</p>
            </div>
            <div class="bg-red-100 p-4 rounded-full">
                <i class="fas fa-user-slash text-3xl text-red-600"></i>
            </div>
        </div>
    </div>
</div>

{{-- Search and Filter --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Tên, email, số điện thoại..." 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Tất cả</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>

        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-search mr-2"></i>Tìm kiếm
            </button>
            <a href="{{ route('admin.customers.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </div>
    </form>
</div>

{{-- Customers Table --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Khách hàng</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Email</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Số điện thoại</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Đơn hàng</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Trạng thái</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-6">
                        <span class="font-semibold">#{{ $customer->customer_id }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $customer->user->user_name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $customer->user->user_address ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="text-gray-600">{{ $customer->user->user_email ?? 'N/A' }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="text-gray-600">{{ $customer->user->user_phone ?? 'Chưa có' }}</span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $customer->orders ? $customer->orders->count() : 0 }} đơn
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($customer->user)
                        <button onclick="toggleStatus({{ $customer->customer_id }})" 
                                class="status-toggle inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $customer->user->user_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $customer->user->user_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            <span class="status-text">{{ $customer->user->user_active ? 'Hoạt động' : 'Không hoạt động' }}</span>
                        </button>
                        @else
                        <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('admin.customers.show', $customer->customer_id) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition text-sm"
                               title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer->customer_id) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition text-sm"
                               title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @php
                                $orderCount = $customer->orders ? $customer->orders->count() : 0;
                            @endphp
                            @if($orderCount == 0)
                            <form action="{{ route('admin.customers.destroy', $customer->customer_id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition text-sm"
                                        title="Xóa">
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
                            <p class="text-lg">Không tìm thấy khách hàng nào</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $customers->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function toggleStatus(customerId) {
    if (!confirm('Bạn có chắc muốn thay đổi trạng thái khách hàng này?')) {
        return;
    }

    fetch(`/admin/customers/${customerId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = event.target.closest('button');
            const statusText = button.querySelector('.status-text');
            
            if (data.is_active) {
                button.className = 'status-toggle inline-block px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800';
                button.innerHTML = '<i class="fas fa-check-circle mr-1"></i><span class="status-text">Hoạt động</span>';
            } else {
                button.className = 'status-toggle inline-block px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800';
                button.innerHTML = '<i class="fas fa-times-circle mr-1"></i><span class="status-text">Không hoạt động</span>';
            }
            
            alert(data.message);
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thay đổi trạng thái');
    });
}
</script>
@endpush
