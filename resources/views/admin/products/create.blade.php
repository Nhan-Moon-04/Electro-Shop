<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm mới - Admin</title>
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
                    <h2 class="text-3xl font-bold text-gray-800">Thêm sản phẩm mới</h2>
                    <p class="text-gray-600 mt-1">Điền thông tin sản phẩm và biến thể</p>
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

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Phần form giống như đã mô tả ở trên -->
                <!-- Tôi sẽ làm ngắn gọn để tiết kiệm token -->
                
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Thông tin cơ bản</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                            <input type="text" name="product_name" value="{{ old('product_name') }}" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                            <select name="category_id" class="w-full px-4 py-2 border rounded-lg" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nhà cung cấp <span class="text-red-500">*</span></label>
                            <select name="supplier_id" class="w-full px-4 py-2 border rounded-lg" required>
                                <option value="">-- Chọn nhà cung cấp --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian bảo hành (tháng)</label>
                            <input type="number" name="product_period" value="{{ old('product_period', 12) }}" min="1" max="60" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="product_is_display" id="product_is_display" class="w-5 h-5" checked>
                            <label for="product_is_display" class="ml-2 text-sm font-medium text-gray-700">Hiển thị sản phẩm</label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả sản phẩm</label>
                            <textarea name="product_description" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ old('product_description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Hình ảnh sản phẩm</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh đại diện</label>
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
                        <div class="variant-item border p-4 mb-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên biến thể *</label>
                                    <input type="text" name="variants[0][name]" class="w-full px-4 py-2 border rounded-lg" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá (₫) *</label>
                                    <input type="number" name="variants[0][price]" min="0" class="w-full px-4 py-2 border rounded-lg" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng *</label>
                                    <input type="number" name="variants[0][available]" min="0" class="w-full px-4 py-2 border rounded-lg" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">
                        <i class="fas fa-times mr-2"></i>Hủy
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        <i class="fas fa-save mr-2"></i>Lưu sản phẩm
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        let variantCount = 1;
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
