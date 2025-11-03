@extends('layouts.admin')

@section('title', 'Sửa nhà cung cấp')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Sửa nhà cung cấp</h2>
            <p class="text-gray-600 text-sm mt-1">Cập nhật thông tin nhà cung cấp</p>
        </div>
        <a href="{{ route('admin.suppliers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
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
    <form action="{{ route('admin.suppliers.update', $supplier->supplier_id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Tên nhà cung cấp -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-3">Tên nhà cung cấp <span class="text-red-500">*</span></label>
                <input type="text" name="supplier_name" value="{{ old('supplier_name', $supplier->supplier_name) }}" 
                    class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('supplier_name') border-red-500 @enderror" 
                    required>
                @error('supplier_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hiển thị -->
            <div class="flex items-center">
                <input type="checkbox" name="supplier_is_display" id="supplier_is_display" class="w-6 h-6 text-blue-600" 
                    {{ old('supplier_is_display', $supplier->supplier_is_display) == 1 ? 'checked' : '' }}>
                <label for="supplier_is_display" class="ml-3 text-lg font-medium text-gray-700">Hiển thị nhà cung cấp</label>
            </div>

            <!-- Logo nhà cung cấp hiện tại -->
            <div class="md:col-span-2">
                <label class="block text-lg font-medium text-gray-700 mb-3">Logo nhà cung cấp hiện tại</label>
                @if($supplier->supplier_logo)
                    <img src="{{ asset('imgs/suppliers_logo/' . $supplier->supplier_logo) }}" 
                        alt="{{ $supplier->supplier_name }}" 
                        class="w-64 h-64 object-cover rounded-lg border-4 border-blue-500 shadow-lg mb-4">
                @else
                    <p class="text-gray-500">Chưa có logo</p>
                @endif
            </div>

            <!-- Thay đổi logo -->
            <div class="md:col-span-2">
                <label class="block text-lg font-medium text-gray-700 mb-3">Thay đổi logo (nếu cần)</label>
                <input type="file" name="supplier_logo" accept="image/*" 
                    class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('supplier_logo') border-red-500 @enderror" 
                    onchange="previewImage(event)">
                @error('supplier_logo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div id="preview" class="mt-6"></div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-6 justify-end mt-8 pt-6 border-t">
            <a href="{{ route('admin.suppliers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 text-lg rounded-lg transition">
                <i class="fas fa-times mr-2"></i>Hủy
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 text-lg rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Cập nhật nhà cung cấp
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
            preview.innerHTML = `
                <div>
                    <p class="text-gray-700 font-medium mb-2">Ảnh mới:</p>
                    <img src="${e.target.result}" class="w-64 h-64 object-cover rounded-lg border-4 border-green-500 shadow-lg">
                </div>
            `;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
