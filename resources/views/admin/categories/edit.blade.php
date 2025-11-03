@extends('layouts.admin')<!DOCTYPE html>

<html lang="vi">

@section('title', 'Sửa danh mục')<head>

    <meta charset="UTF-8">

@section('content')    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="max-w-3xl">    <title>Sửa danh mục - Admin</title>

    <!-- Header -->    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="flex items-center justify-between mb-6">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <div></head>

            <h2 class="text-2xl font-bold text-gray-800">Sửa danh mục</h2><body class="bg-gray-50">

            <p class="text-gray-600 text-sm mt-1">Cập nhật thông tin danh mục</p>    <div class="flex min-h-screen">

        </div>        <!-- Sidebar -->

        <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">        <aside class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white fixed h-screen">

            <i class="fas fa-arrow-left mr-2"></i>Quay lại            <div class="p-6">

        </a>                <h1 class="text-2xl font-bold flex items-center">

    </div>                    <i class="fas fa-bolt text-yellow-300 mr-2"></i>

                    ElectroShop

    <form action="{{ route('admin.categories.update', $category->category_id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">                </h1>

        @csrf                <p class="text-sm text-blue-200 mt-1">Admin Panel</p>

        @method('PUT')            </div>

                    

        <div class="space-y-6">            <nav class="mt-6">

            <div>                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition">

                <label class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>                    <i class="fas fa-chart-line mr-3"></i>

                <input type="text" name="category_name" value="{{ old('category_name', $category->category_name) }}" class="w-full px-4 py-2 border rounded-lg @error('category_name') border-red-500 @enderror" required>                    Dashboard

                @error('category_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror                </a>

            </div>                <a href="{{ route('admin.products.index') }}" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition">

                                <i class="fas fa-box mr-3"></i>

            <div>                    Sản phẩm

                <label class="block text-sm font-medium text-gray-700 mb-2">Loại danh mục <span class="text-red-500">*</span></label>                </a>

                <select name="category_type" class="w-full px-4 py-2 border rounded-lg @error('category_type') border-red-500 @enderror" required>                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 bg-blue-700 text-white transition">

                    <option value="">-- Chọn loại --</option>                    <i class="fas fa-tags mr-3"></i>

                    <option value="Điện máy" {{ old('category_type', $category->categorry_type) == 'Điện máy' ? 'selected' : '' }}>Điện máy</option>                    Danh mục

                    <option value="Điện tử" {{ old('category_type', $category->categorry_type) == 'Điện tử' ? 'selected' : '' }}>Điện tử</option>                </a>

                    <option value="Đồ dùng nhà bếp" {{ old('category_type', $category->categorry_type) == 'Đồ dùng nhà bếp' ? 'selected' : '' }}>Đồ dùng nhà bếp</option>            </nav>

                    <option value="Gia dụng" {{ old('category_type', $category->categorry_type) == 'Gia dụng' ? 'selected' : '' }}>Gia dụng</option>        </aside>

                </select>

                @error('category_type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror        <!-- Main Content -->

            </div>        <main class="flex-1 ml-64 p-8">

            <!-- Header -->

            <div>            <div class="flex items-center justify-between mb-8">

                <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh danh mục hiện tại</label>                <div>

                @if($category->category_img)                    <h2 class="text-3xl font-bold text-gray-800">Sửa danh mục</h2>

                    <img src="{{ asset('imgs/categories/' . $category->category_img) }}" class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500 mb-3">                    <p class="text-gray-600 mt-1">Cập nhật thông tin danh mục</p>

                @endif                </div>

                                <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition">

                <label class="block text-sm font-medium text-gray-700 mb-2">Thay đổi ảnh (nếu cần)</label>                    <i class="fas fa-arrow-left mr-2"></i>

                <input type="file" name="category_img" accept="image/*" class="w-full px-4 py-2 border rounded-lg @error('category_img') border-red-500 @enderror" onchange="previewImage(event)">                    Quay lại

                @error('category_img')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror                </a>

                <div id="preview" class="mt-4"></div>            </div>

            </div>

            @if($errors->any())

            <div class="flex items-center">            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">

                <input type="checkbox" name="category_is_display" id="category_is_display" class="w-5 h-5" {{ old('category_is_display', $category->category_is_display) == 1 ? 'checked' : '' }}>                <strong class="font-bold">Có lỗi xảy ra!</strong>

                <label for="category_is_display" class="ml-2 text-sm font-medium text-gray-700">Hiển thị danh mục</label>                <ul class="mt-2 list-disc list-inside">

            </div>                    @foreach ($errors->all() as $error)

        </div>                        <li>{{ $error }}</li>

                    @endforeach

        <div class="flex gap-4 justify-end mt-6 pt-6 border-t">                </ul>

            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">            </div>

                <i class="fas fa-times mr-2"></i>Hủy            @endif

            </a>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">            <form action="{{ route('admin.categories.update', $category->category_id) }}" method="POST" enctype="multipart/form-data">

                <i class="fas fa-save mr-2"></i>Cập nhật danh mục                @csrf

            </button>                @method('PUT')

        </div>                

    </form>                <div class="bg-white rounded-lg shadow-md p-6 mb-6">

</div>                    <h3 class="text-xl font-bold text-gray-800 mb-4">Thông tin danh mục</h3>

@endsection                    

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

@push('scripts')                        <div>

<script>                            <label class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>

function previewImage(event) {                            <input type="text" name="category_name" value="{{ old('category_name', $category->category_name) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>

    const preview = document.getElementById('preview');                        </div>

    const file = event.target.files[0];                        

    if (file) {                        <div>

        const reader = new FileReader();                            <label class="block text-sm font-medium text-gray-700 mb-2">Loại danh mục <span class="text-red-500">*</span></label>

        reader.onload = function(e) {                            <select name="category_type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>

            preview.innerHTML = `                                <option value="">-- Chọn loại --</option>

                <p class="text-sm text-gray-600 mb-2">Ảnh mới:</p>                                <option value="Điện máy" {{ old('category_type', $category->category_type) == 'Điện máy' ? 'selected' : '' }}>Điện máy</option>

                <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg border-2 border-green-500">                                <option value="Điện tử" {{ old('category_type', $category->category_type) == 'Điện tử' ? 'selected' : '' }}>Điện tử</option>

            `;                                <option value="Đồ dùng nhà bếp" {{ old('category_type', $category->category_type) == 'Đồ dùng nhà bếp' ? 'selected' : '' }}>Đồ dùng nhà bếp</option>

        }                                <option value="Gia dụng" {{ old('category_type', $category->category_type) == 'Gia dụng' ? 'selected' : '' }}>Gia dụng</option>

        reader.readAsDataURL(file);                            </select>

    }                        </div>

}

</script>                        <div class="md:col-span-2">

@endpush                            <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh danh mục hiện tại</label>

                            @if($category->category_img)
                                <img src="{{ asset('imgs/categories/' . $category->category_img) }}" class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500 mb-3">
                            @endif
                            
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thay đổi ảnh (nếu cần)</label>
                            <input type="file" name="category_img" accept="image/*" class="w-full px-4 py-2 border rounded-lg" onchange="previewImage(event)">
                            <div id="preview" class="mt-4"></div>
                        </div>

                        <div class="md:col-span-2 flex items-center">
                            <input type="checkbox" name="category_is_display" id="category_is_display" class="w-5 h-5" {{ old('category_is_display', $category->category_is_display) == 1 ? 'checked' : '' }}>
                            <label for="category_is_display" class="ml-2 text-sm font-medium text-gray-700">Hiển thị danh mục</label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">
                        <i class="fas fa-times mr-2"></i>Hủy
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        <i class="fas fa-save mr-2"></i>Cập nhật danh mục
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <p class="text-sm text-gray-600 mb-2">Ảnh mới:</p>
                        <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg border-2 border-green-500">
                    `;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
