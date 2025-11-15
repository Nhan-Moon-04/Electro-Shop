<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm - Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar (giống index) -->
        <aside class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white fixed h-screen">
            <div class="p-6">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-bolt text-yellow-300 mr-2"></i>
                    ElectroShop
                </h1>
                <p class="text-sm text-blue-200 mt-1">Admin Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-6 py-3 bg-blue-700 text-white transition">
                    <i class="fas fa-box mr-3"></i>
                    Sản phẩm
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Sửa sản phẩm</h2>
                    <p class="text-gray-600 mt-1">Cập nhật thông tin sản phẩm và biến thể</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>

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

            <form action="{{ route('admin.products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Phần form giống như đã mô tả ở trên -->
                <!-- Tôi sẽ làm ngắn gọn để tiết kiệm token -->
                
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Thông tin cơ bản</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                            <input type="text" name="product_name" value="{{ old('product_name', $product->product_name) }}" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                            <select name="category_id" class="w-full px-4 py-2 border rounded-lg" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}" {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nhà cung cấp <span class="text-red-500">*</span></label>
                            <select name="supplier_id" class="w-full px-4 py-2 border rounded-lg" required>
                                <option value="">-- Chọn nhà cung cấp --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->supplier_id ? 'selected' : '' }}>{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian bảo hành (tháng)</label>
                            <input type="number" name="product_period" value="{{ old('product_period', $product->product_period) }}" min="1" max="60" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="product_is_display" id="product_is_display" class="w-5 h-5" {{ old('product_is_display', $product->product_is_display) == 1 ? 'checked' : '' }}>
                            <label for="product_is_display" class="ml-2 text-sm font-medium text-gray-700">Hiển thị sản phẩm</label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả sản phẩm</label>
                            <textarea name="product_description" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ old('product_description', $product->product_description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Hình ảnh sản phẩm</h3>
                    
                    <!-- Ảnh hiện tại -->
                    @if($product->product_avt_img || $product->images->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Hình ảnh hiện tại</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if($product->product_avt_img)
                            <div class="relative group">
                                <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}" class="w-full h-32 object-cover rounded-lg border-2 border-blue-500">
                                <span class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">Đại diện</span>
                            </div>
                            @endif
                            @foreach($product->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $image->image_name) }}" class="w-full h-32 object-cover rounded-lg">
                                <button type="button" onclick="deleteImage({{ $image->image_id }})" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh đại diện mới (nếu cần thay đổi)</label>
                            <input type="file" name="product_avt_img" accept="image/*" class="w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh bổ sung</label>
                            <input type="file" name="images[]" accept="image/*" multiple class="w-full">
                        </div>
                    </div>
                </div>

                <!-- Biến thể -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Biến thể sản phẩm</h3>
                        <button type="button" onclick="addVariant()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-2"></i>Thêm biến thể
                        </button>
                    </div>
                    <div id="variants-container">
                        @foreach($product->variants as $index => $variant)
                        <div class="variant-item border p-4 mb-4 rounded-lg">
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->product_variant_id }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên biến thể *</label>
                                    <input type="text" name="variants[{{ $index }}][name]" value="{{ old('variants.'.$index.'.name', $variant->product_variant_name) }}" class="w-full px-4 py-2 border rounded-lg" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá (₫) *</label>
                                    <input type="number" name="variants[{{ $index }}][price]" value="{{ old('variants.'.$index.'.price', $variant->product_variant_price) }}" min="0" class="w-full px-4 py-2 border rounded-lg" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng *</label>
                                    <input type="number" name="variants[{{ $index }}][available]" value="{{ old('variants.'.$index.'.available', $variant->product_variant_available) }}" min="0" class="w-full px-4 py-2 border rounded-lg" required>
                                </div>
                            </div>
                            @if($index > 0)
                            <button type="button" onclick="this.closest('.variant-item').remove()" class="mt-2 text-red-600 hover:text-red-800">
                                <i class="fas fa-trash mr-1"></i>Xóa biến thể
                            </button>
                            @endif
                        </div>
                        @endforeach
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">
                        <i class="fas fa-times mr-2"></i>Hủy
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        <i class="fas fa-save mr-2"></i>Cập nhật sản phẩm
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        let variantCount = {{ $product->variants->count() }};
        
        function deleteImage(imageId) {
            if(confirm('Bạn có chắc muốn xóa ảnh này?')) {
                fetch(`/admin/products/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra!');
                });
            }
        }
        
        function addVariant() {
            const container = document.getElementById('variants-container');
            const html = `
                <div class="variant-item border p-4 mb-4 rounded-lg relative">
                    <button type="button" onclick="this.closest('.variant-item').remove()" class="absolute top-2 right-2 bg-red-500 text-white w-8 h-8 rounded-full">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tên biến thể *</label>
                            <input type="text" name="variants[${variantCount}][name]" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Giá (₫) *</label>
                            <input type="number" name="variants[${variantCount}][price]" min="0" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng *</label>
                            <input type="number" name="variants[${variantCount}][available]" min="0" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            variantCount++;
        }
    </script>
</body>
</html>
