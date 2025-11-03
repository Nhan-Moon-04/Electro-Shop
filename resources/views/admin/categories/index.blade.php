@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Quản lý danh mục</h2>
        <p class="text-gray-600 text-sm mt-1">Thêm, sửa, xóa danh mục sản phẩm</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center transition shadow-md">
        <i class="fas fa-plus mr-2"></i>
        Thêm danh mục mới
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form action="{{ route('admin.categories.index') }}" method="GET" id="searchForm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm danh mục..." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <select name="type" onchange="document.getElementById('searchForm').submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tất cả loại</option>
                    <option value="Điện máy" {{ request('type') == 'Điện máy' ? 'selected' : '' }}>Điện máy</option>
                    <option value="Điện tử" {{ request('type') == 'Điện tử' ? 'selected' : '' }}>Điện tử</option>
                    <option value="Đồ dùng nhà bếp" {{ request('type') == 'Đồ dùng nhà bếp' ? 'selected' : '' }}>Đồ dùng nhà bếp</option>
                    <option value="Gia dụng" {{ request('type') == 'Gia dụng' ? 'selected' : '' }}>Gia dụng</option>
                </select>
            </div>
            <div>
                <select name="status" onchange="document.getElementById('searchForm').submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Trạng thái</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i>Tìm kiếm
            </button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">ID</th>
                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Hình ảnh</th>
                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Tên danh mục</th>
                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Loại</th>
                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Số sản phẩm</th>
                <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Trạng thái</th>
                <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="py-4 px-6 text-sm text-gray-700">{{ $category->category_id }}</td>
                <td class="py-4 px-6">
                    @if($category->category_img)
                        <img src="{{ asset('imgs/categories/' . $category->category_img) }}" alt="{{ $category->category_name }}" class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <p class="font-semibold text-gray-800">{{ $category->category_name }}</p>
                </td>
                <td class="py-4 px-6">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $category->categorry_type }}
                    </span>
                </td>
                <td class="py-4 px-6 text-sm text-gray-600">
                    {{ $category->products()->count() }} sản phẩm
                </td>
                <td class="py-4 px-6 text-center">
                    @if($category->category_is_display == 1)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Hiển thị
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Ẩn
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.categories.edit', $category->category_id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg transition">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->category_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-12 text-center">
                    <div class="text-gray-400">
                        <i class="fas fa-tags text-5xl mb-4"></i>
                        <p class="text-lg">Chưa có danh mục nào</p>
                        <a href="{{ route('admin.categories.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                            Thêm danh mục đầu tiên
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $categories->appends(request()->query())->links() }}
</div>
@endsection

@push('scripts')
<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    const inputs = this.querySelectorAll('input[type="text"], select');
    inputs.forEach(input => {
        if (input.value === '' || input.value === null) {
            input.removeAttribute('name');
        }
    });
});
</script>
@endpush
