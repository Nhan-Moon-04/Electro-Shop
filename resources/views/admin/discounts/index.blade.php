@extends('layouts.admin')

@section('title', 'Quản lý khuyến mãi')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Quản lý khuyến mãi</h1>
            <p class="text-gray-600 mt-1">Thêm, sửa, xóa và theo dõi các chương trình khuyến mãi</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.discounts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i>Tạo khuyến mãi mới
            </a>
        </div>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 relative" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

{{-- Status Summary --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-md p-4 text-center border-t-4 border-green-400 hover:shadow-lg transition">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-check-circle text-2xl text-green-600"></i>
        </div>
        <p class="text-2xl font-bold text-green-600">{{ \App\Models\Discount::where('discount_is_display', 1)->where('discount_end_date', '>=', now())->count() }}</p>
        <p class="text-sm text-gray-600 mt-1">Đang hoạt động</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-4 text-center border-t-4 border-blue-400 hover:shadow-lg transition">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-clock text-2xl text-blue-600"></i>
        </div>
        <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Discount::where('discount_start_date', '>', now())->count() }}</p>
        <p class="text-sm text-gray-600 mt-1">Sắp diễn ra</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-4 text-center border-t-4 border-red-400 hover:shadow-lg transition">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-calendar-times text-2xl text-red-600"></i>
        </div>
        <p class="text-2xl font-bold text-red-600">{{ \App\Models\Discount::where('discount_end_date', '<', now())->count() }}</p>
        <p class="text-sm text-gray-600 mt-1">Hết hạn</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-4 text-center border-t-4 border-yellow-400 hover:shadow-lg transition">
        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-eye-slash text-2xl text-yellow-600"></i>
        </div>
        <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\Discount::where('discount_is_display', 0)->count() }}</p>
        <p class="text-sm text-gray-600 mt-1">Đã ẩn</p>
    </div>
</div>

{{-- Discounts Table --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Tên khuyến mãi</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Giảm giá</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Thời gian</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Trạng thái</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Hiển thị</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($discounts as $discount)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-6">
                        <span class="font-semibold text-gray-800">#{{ $discount->discount_id }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-medium text-gray-800">{{ $discount->discount_name }}</p>
                        @if($discount->discount_description)
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($discount->discount_description, 60) }}</p>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">
                            {{ $discount->discount_amount }}%
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-sm text-gray-800">
                            <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                            {{ \Carbon\Carbon::parse($discount->discount_start_date)->format('d/m/Y') }}
                        </p>
                        <p class="text-sm text-gray-800 mt-1">
                            <i class="fas fa-calendar-check text-gray-400 mr-1"></i>
                            {{ \Carbon\Carbon::parse($discount->discount_end_date)->format('d/m/Y') }}
                        </p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @php
                            $now = now();
                            $start = \Carbon\Carbon::parse($discount->discount_start_date);
                            $end = \Carbon\Carbon::parse($discount->discount_end_date);
                            
                            if ($end->lt($now)) {
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusIcon = 'fa-calendar-times';
                                $statusText = 'Hết hạn';
                            } elseif ($discount->discount_is_display && $start->lte($now) && $end->gte($now)) {
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusIcon = 'fa-check-circle';
                                $statusText = 'Đang hoạt động';
                            } elseif ($start->gt($now)) {
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusIcon = 'fa-clock';
                                $statusText = 'Sắp diễn ra';
                            } else {
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusIcon = 'fa-times-circle';
                                $statusText = 'Không hoạt động';
                            }
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            <i class="fas {{ $statusIcon }} mr-1"></i>
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer toggle-display" 
                                   data-id="{{ $discount->discount_id }}"
                                   {{ $discount->discount_is_display ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('admin.discounts.statistics', $discount->discount_id) }}" 
                               class="text-purple-600 hover:text-purple-800 transition" 
                               title="Thống kê">
                                <i class="fas fa-chart-line"></i>
                            </a>
                            <a href="{{ route('admin.discounts.edit', $discount->discount_id) }}" 
                               class="text-blue-600 hover:text-blue-800 transition" 
                               title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="text-red-600 hover:text-red-800 transition delete-btn" 
                                    data-id="{{ $discount->discount_id }}"
                                    title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-inbox text-6xl mb-4"></i>
                            <p class="text-lg">Chưa có khuyến mãi nào</p>
                            <a href="{{ route('admin.discounts.create') }}" class="text-blue-500 hover:underline mt-2 inline-block">
                                Tạo khuyến mãi đầu tiên
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($discounts->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $discounts->links() }}
    </div>
    @endif
</div>

{{-- Delete Form --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
// Toggle display status
document.querySelectorAll('.toggle-display').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const discountId = this.dataset.id;
        const checkbox = this;
        
        fetch(`/admin/discounts/${discountId}/toggle-display`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Show notification (you can use toast library)
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                message.textContent = data.message;
                document.body.appendChild(message);
                
                setTimeout(() => {
                    message.remove();
                    location.reload(); // Reload to update status badge
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = !checkbox.checked; // Revert on error
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        });
    });
});

// Delete discount
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?\nLưu ý: Các sản phẩm đang áp dụng khuyến mãi này sẽ bị ảnh hưởng.')) {
            const discountId = this.dataset.id;
            const form = document.getElementById('deleteForm');
            form.action = `/admin/discounts/${discountId}`;
            form.submit();
        }
    });
});
</script>
@endpush
