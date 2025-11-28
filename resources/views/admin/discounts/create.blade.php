@extends('layouts.admin')

@section('title', 'Tạo khuyến mãi mới')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tạo khuyến mãi mới</h1>
            <p class="text-gray-600 mt-1">Thiết lập chương trình khuyến mãi cho sản phẩm</p>
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
            <form action="{{ route('admin.discounts.store') }}" method="POST" id="discountForm">
                @csrf
                <input type="hidden" name="_token_admin" id="adminTokenField">

                {{-- Tên khuyến mãi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tên khuyến mãi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="discount_name" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('discount_name') border-red-500 @enderror" 
                           value="{{ old('discount_name') }}" 
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
                              placeholder="Mô tả chi tiết về chương trình khuyến mãi...">{{ old('discount_description') }}</textarea>
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
                               value="{{ old('discount_start_date', now()->format('Y-m-d')) }}" 
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
                               value="{{ old('discount_end_date', now()->addDays(30)->format('Y-m-d')) }}" 
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
                               value="{{ old('discount_amount') }}" 
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
                               {{ old('discount_is_display', true) ? 'checked' : '' }}>
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
                        <i class="fas fa-save mr-2"></i>Tạo khuyến mãi
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
        <div class="bg-blue-50 rounded-lg shadow-md p-6 sticky top-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Hướng dẫn
            </h3>
            
            <div class="space-y-4 text-sm text-gray-700">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <div>
                        <p class="font-medium">Tên khuyến mãi</p>
                        <p class="text-gray-600">Nên ngắn gọn, dễ nhớ và mô tả rõ chương trình</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <div>
                        <p class="font-medium">Phần trăm giảm giá</p>
                        <p class="text-gray-600">Từ 0-100%, ví dụ: 10%, 25%, 50%</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <div>
                        <p class="font-medium">Thời gian áp dụng</p>
                        <p class="text-gray-600">Ngày kết thúc phải sau ngày bắt đầu</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <div>
                        <p class="font-medium">Hiển thị khuyến mãi</p>
                        <p class="text-gray-600">Bật để khuyến mãi có hiệu lực ngay</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Lưu ý:</strong> Sau khi tạo, hãy vào phần sản phẩm để áp dụng khuyến mãi cho các sản phẩm.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Set admin token to hidden field
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('admin_token');
    if (token) {
        document.getElementById('adminTokenField').value = token;
    }
});

// Validate dates
document.getElementById('discountForm').addEventListener('submit', function(e) {
    const startDate = new Date(document.querySelector('input[name="discount_start_date"]').value);
    const endDate = new Date(document.querySelector('input[name="discount_end_date"]').value);
    
    if (endDate <= startDate) {
        e.preventDefault();
        alert('Ngày kết thúc phải sau ngày bắt đầu!');
        return false;
    }
    
    // Make sure token is set before submit
    const token = localStorage.getItem('admin_token');
    if (token) {
        document.getElementById('adminTokenField').value = token;
    }
});

// Auto-fill discount name suggestions
const discountNameInput = document.querySelector('input[name="discount_name"]');
const suggestions = [
    'Black Friday',
    'Tết Nguyên Đán',
    'Giảm giá mùa hè',
    '11/11 Sale',
    'Back to School',
    'Giáng Sinh',
    'Khai trương',
    'Flash Sale',
];

// You can add autocomplete functionality here if needed
</script>
@endpush
