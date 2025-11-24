@extends('layouts.admin')

@section('title', 'Chỉnh sửa khuyến mãi')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Chỉnh sửa khuyến mãi</h1>
            <p class="text-gray-600 mt-1">Cập nhật thông tin chương trình khuyến mãi</p>
        </div>
        <a href="{{ route('admin.discounts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
    </div>
</div>

{{-- Form --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.discounts.update', $discount->discount_id) }}" method="POST" id="discountForm">
                @csrf
                @method('PUT')

                {{-- Tên khuyến mãi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tên khuyến mãi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="discount_name" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('discount_name') border-red-500 @enderror" 
                           value="{{ old('discount_name', $discount->discount_name) }}" 
                           placeholder="VD: Black Friday, Tết 2024, ..."
                           required>
                    @error('discount_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả chi tiết
                    </label>
                    <textarea name="discount_description" 
                              rows="4" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('discount_description') border-red-500 @enderror"
                              placeholder="Mô tả chi tiết về chương trình khuyến mãi...">{{ old('discount_description', $discount->discount_description) }}</textarea>
                    @error('discount_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ngày bắt đầu & Ngày kết thúc --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ngày bắt đầu <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="discount_start_date" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('discount_start_date') border-red-500 @enderror" 
                               value="{{ old('discount_start_date', $discount->discount_start_date->format('Y-m-d')) }}" 
                               required>
                        @error('discount_start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ngày kết thúc <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="discount_end_date" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('discount_end_date') border-red-500 @enderror" 
                               value="{{ old('discount_end_date', $discount->discount_end_date->format('Y-m-d')) }}" 
                               required>
                        @error('discount_end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Phần trăm giảm giá --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phần trăm giảm giá (%) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="discount_amount" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-primary @error('discount_amount') border-red-500 @enderror" 
                               value="{{ old('discount_amount', $discount->discount_amount) }}" 
                               min="0" 
                               max="100" 
                               step="0.01"
                               placeholder="10"
                               required>
                        <span class="absolute right-3 top-2.5 text-gray-500">%</span>
                    </div>
                    @error('discount_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Hiển thị khuyến mãi --}}
                <div class="mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="discount_is_display" 
                               class="sr-only peer"
                               {{ old('discount_is_display', $discount->discount_is_display) ? 'checked' : '' }}>
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Hiển thị khuyến mãi</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-14">
                        Khuyến mãi sẽ được áp dụng ngay khi bật hiển thị
                    </p>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center space-x-3 pt-4 border-t">
                    <button type="submit" class="bg-primary hover:bg-primary-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Cập nhật khuyến mãi
                    </button>
                    <a href="{{ route('admin.discounts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="lg:col-span-1">
        {{-- Current Status --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Thông tin hiện tại
            </h3>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center pb-2 border-b">
                    <span class="text-gray-600">ID:</span>
                    <span class="font-semibold">#{{ $discount->discount_id }}</span>
                </div>

                <div class="flex justify-between items-center pb-2 border-b">
                    <span class="text-gray-600">Trạng thái:</span>
                    <span>{!! $discount->getStatusBadge() !!}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Số ngày còn lại:</span>
                    @php
                        $daysRemaining = now()->diffInDays($discount->discount_end_date, false);
                    @endphp
                    <span class="font-semibold {{ $daysRemaining < 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $daysRemaining < 0 ? 'Đã hết hạn' : $daysRemaining . ' ngày' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Guidelines --}}
        <div class="bg-blue-50 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Lưu ý khi chỉnh sửa
            </h3>
            
            <div class="space-y-3 text-sm text-gray-700">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-yellow-500 mr-2 mt-1"></i>
                    <p>Thay đổi phần trăm giảm giá sẽ ảnh hưởng đến tất cả sản phẩm đang áp dụng</p>
                </div>

                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-yellow-500 mr-2 mt-1"></i>
                    <p>Tắt hiển thị sẽ ngừng áp dụng khuyến mãi cho đơn hàng mới</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Validate dates
document.getElementById('discountForm').addEventListener('submit', function(e) {
    const startDate = new Date(document.querySelector('input[name="discount_start_date"]').value);
    const endDate = new Date(document.querySelector('input[name="discount_end_date"]').value);
    
    if (endDate <= startDate) {
        e.preventDefault();
        alert('Ngày kết thúc phải sau ngày bắt đầu!');
        return false;
    }
});
</script>
@endpush
