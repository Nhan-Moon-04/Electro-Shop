@extends('layouts.admin')

@section('title', 'Quản lý nhà cung cấp')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý nhà cung cấp</h2>
            <p class="text-gray-600 text-sm mt-1">Thêm, sửa, xóa nhà cung cấp</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center transition shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Thêm nhà cung cấp mới
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Thành công!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('admin.suppliers.index') }}" method="GET" id="searchForm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Tìm kiếm tên nhà cung cấp..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
                <a href="{{ route('admin.suppliers.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Logo</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Tên nhà cung cấp</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Trạng thái</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $supplier->supplier_id }}</td>
                    <td class="py-4 px-6">
                        @if($supplier->supplier_logo)
                            <img src="{{ asset('imgs/suppliers_logo/' . $supplier->supplier_logo) }}" 
                                alt="{{ $supplier->supplier_name }}" 
                                class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-semibold text-gray-800">{{ $supplier->supplier_name }}</p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($supplier->supplier_is_display == 1)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Hiển thị</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">Ẩn</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.suppliers.edit', $supplier->supplier_id) }}" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white w-10 h-10 rounded-lg flex items-center justify-center transition" 
                                title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            @if($supplier->supplier_is_display == 1)
                                <form action="{{ route('admin.suppliers.destroy', $supplier->supplier_id) }}" method="POST" 
                                    onsubmit="return confirm('Bạn có chắc muốn xóa nhà cung cấp này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-lg flex items-center justify-center transition" 
                                        title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.suppliers.restore', $supplier->supplier_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="bg-green-500 hover:bg-green-600 text-white w-10 h-10 rounded-lg flex items-center justify-center transition" 
                                        title="Hiển thị">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500 text-lg">Chưa có nhà cung cấp nào</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($suppliers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $suppliers->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
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
