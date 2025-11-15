@extends('layouts.admin')

@section('title', 'Thêm danh mục mới')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Thêm danh mục mới</h2>
            <p class="text-gray-600 text-sm mt-1">Điền thông tin danh mục</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Có lỗi xảy ra!</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Tên danh mục -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-3">Tên danh mục <span class="text-red-500">*</span></label>
                <input type="text" name="category_name" value="{{ old('category_name') }}" 
                    class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_name') border-red-500 @enderror" 
                    required>
                @error('category_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Loại danh mục -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-3">Loại danh mục <span class="text-red-500">*</span></label>
                <select name="category_type" 
                    class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_type') border-red-500 @enderror" 
                    required>
                    <option value="">-- Chọn loại --</option>
                    <option value="Điện máy" {{ old('category_type') == 'Điện máy' ? 'selected' : '' }}>Điện máy</option>
                    <option value="Điện tử" {{ old('category_type') == 'Điện tử' ? 'selected' : '' }}>Điện tử</option>
                    <option value="Đồ dùng nhà bếp" {{ old('category_type') == 'Đồ dùng nhà bếp' ? 'selected' : '' }}>Đồ dùng nhà bếp</option>
                    <option value="Gia dụng" {{ old('category_type') == 'Gia dụng' ? 'selected' : '' }}>Gia dụng</option>
                </select>
                @error('category_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ảnh danh mục -->
            <div class="md:col-span-2">
                <label class="block text-lg font-medium text-gray-700 mb-3">Ảnh danh mục <span class="text-red-500">*</span></label>
                <input type="file" name="category_img" accept="image/*" 
                    class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_img') border-red-500 @enderror" 
                    required onchange="previewImage(event)">
                @error('category_img')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div id="preview" class="mt-6"></div>
            </div>

            <!-- Hiển thị -->
            <div class="md:col-span-2 flex items-center">
                <input type="checkbox" name="category_is_display" id="category_is_display" class="w-6 h-6 text-blue-600" checked>
                <label for="category_is_display" class="ml-3 text-lg font-medium text-gray-700">Hiển thị danh mục</label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-6 justify-end mt-8 pt-6 border-t">
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 text-lg rounded-lg transition">
                <i class="fas fa-times mr-2"></i>Hủy
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 text-lg rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Lưu danh mục
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-64 h-64 object-cover rounded-lg border-4 border-blue-500 shadow-lg">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
