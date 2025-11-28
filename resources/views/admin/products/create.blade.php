@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Thêm sản phẩm mới</h2>
            <p class="text-gray-600 text-sm mt-1">Điền thông tin sản phẩm và biến thể</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
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
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Thông tin cơ bản -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Thông tin cơ bản</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Tên sản phẩm -->
                <div class="md:col-span-2">
                    <label class="block text-lg font-medium text-gray-700 mb-3">Tên sản phẩm <span class="text-red-500">*</span></label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" 
                        class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Danh mục -->
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">Danh mục <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nhà cung cấp -->
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">Nhà cung cấp <span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Chọn nhà cung cấp --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Thời gian bảo hành -->
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">Thời gian bảo hành (tháng)</label>
                    <input type="number" name="product_warranty_period" value="{{ old('product_warranty_period', 12) }}" 
                        class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Hiển thị sản phẩm -->
                <div class="flex items-center">
                    <input type="checkbox" name="product_is_display" id="product_is_display" class="w-6 h-6 text-blue-600" checked>
                    <label for="product_is_display" class="ml-3 text-lg font-medium text-gray-700">Hiển thị sản phẩm</label>
                </div>

                <!-- Mô tả sản phẩm -->
                <div class="md:col-span-2">
                    <label class="block text-lg font-medium text-gray-700 mb-3">Mô tả sản phẩm</label>
                    <textarea name="product_description" rows="5" 
                        class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('product_description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Hình ảnh sản phẩm -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Hình ảnh sản phẩm</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Ảnh đại diện -->
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">Ảnh đại diện <span class="text-red-500">*</span></label>
                    <input type="file" name="product_avt_img" accept="image/*" 
                        class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Ảnh bổ sung -->
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">Ảnh bổ sung</label>
                    <input type="file" name="additional_images[]" accept="image/*" multiple 
                        class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Biến thể sản phẩm -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">Biến thể sản phẩm</h3>
                <button type="button" onclick="addVariant()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 text-lg rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Thêm biến thể
                </button>
            </div>

            <div id="variants-container" class="space-y-6">
                <!-- Biến thể mặc định -->
                <div class="variant-item border-2 border-gray-200 rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-3">Tên biến thể <span class="text-red-500">*</span></label>
                            <input type="text" name="variants[0][name]" 
                                class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-3">Giá (₫) <span class="text-red-500">*</span></label>
                            <input type="number" name="variants[0][price]" 
                                class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-3">Số lượng <span class="text-red-500">*</span></label>
                            <input type="number" name="variants[0][available]" 
                                class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-6 justify-end">
            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 text-lg rounded-lg transition">
                <i class="fas fa-times mr-2"></i>Hủy
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 text-lg rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Lưu sản phẩm
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let variantCount = 1;

function addVariant() {
    const container = document.getElementById('variants-container');
    const newVariant = document.createElement('div');
    newVariant.className = 'variant-item border-2 border-gray-200 rounded-lg p-6 relative';
    newVariant.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-full transition">
            <i class="fas fa-times"></i>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-3">Tên biến thể <span class="text-red-500">*</span></label>
                <input type="text" name="variants[${variantCount}][name]" 
                    class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>

            <div>
                <label class="block text-lg font-medium text-gray-700 mb-3">Giá (₫) <span class="text-red-500">*</span></label>
                <input type="number" name="variants[${variantCount}][price]" 
                    class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>

            <div>
                <label class="block text-lg font-medium text-gray-700 mb-3">Số lượng <span class="text-red-500">*</span></label>
                <input type="number" name="variants[${variantCount}][available]" 
                    class="w-full px-6 py-4 text-lg border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>
        </div>
    `;
    container.appendChild(newVariant);
    variantCount++;
}
</script>
@endpush
