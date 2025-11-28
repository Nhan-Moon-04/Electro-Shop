@extends('layouts.admin')

@section('title', 'Chỉnh sửa khách hàng')

@section('content')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Chỉnh sửa khách hàng</h1>
            <p class="text-gray-600 mt-1">Cập nhật thông tin khách hàng</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
    </div>
</div>

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.customers.update', $customer->customer_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tên khách hàng <span class="text-red-500">*</span>
                </label>
                <input type="text" name="user_name" 
                       value="{{ old('user_name', $customer->user->user_name ?? '') }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 @error('user_name') border-red-500 @enderror"
                       required>
                @error('user_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <input type="email" 
                       value="{{ $customer->user->user_email ?? '' }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100"
                       disabled>
                <p class="text-xs text-gray-500 mt-1">Email không thể thay đổi</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Số điện thoại
                </label>
                <input type="text" name="user_phone" 
                       value="{{ old('user_phone', $customer->user->user_phone ?? '') }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Trạng thái
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="user_active" 
                           class="sr-only peer"
                           {{ old('user_active', $customer->user->user_active ?? 0) ? 'checked' : '' }}>
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700">Hoạt động</span>
                </label>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Địa chỉ
                </label>
                <textarea name="user_address" rows="3" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2">{{ old('user_address', $customer->user->user_address ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex items-center space-x-3 mt-6 pt-6 border-t">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Cập nhật
            </button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>Hủy
            </a>
        </div>
    </form>
</div>

@endsection
