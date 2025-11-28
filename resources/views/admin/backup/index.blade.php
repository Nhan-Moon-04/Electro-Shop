@extends('layouts.admin')

@section('title', 'Backup & Restore')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Backup & Restore Database</h1>
            <p class="text-gray-600 mt-1">Sao lưu và khôi phục dữ liệu hệ thống</p>
        </div>
        <button onclick="document.getElementById('createBackupForm').submit()" 
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition">
            <i class="fas fa-download mr-2"></i>Tạo Backup Mới
        </button>
    </div>
</div>

<form id="createBackupForm" action="{{ route('admin.backup.create') }}" method="POST" style="display: none;">
    @csrf
</form>

{{-- Success/Error Messages --}}
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

{{-- Restore Section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Restore from Existing Backup --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-history text-blue-500 mr-2"></i>Khôi phục từ Backup
        </h3>
        
        <form id="restoreForm" action="{{ route('admin.backup.restore') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file backup</label>
                <select name="filename" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    <option value="">-- Chọn backup --</option>
                    @foreach($backups as $backup)
                    <option value="{{ $backup['name'] }}">{{ $backup['name'] }} ({{ $backup['size'] }} - {{ $backup['date'] }})</option>
                    @endforeach
                </select>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <p class="font-semibold text-yellow-800">Cảnh báo:</p>
                        <p class="text-sm text-yellow-700">Tất cả dữ liệu hiện tại sẽ bị ghi đè. Hãy chắc chắn bạn đã backup trước khi khôi phục!</p>
                    </div>
                </div>
            </div>

            <button type="button" onclick="confirmRestore()" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-undo mr-2"></i>Khôi phục dữ liệu
            </button>
        </form>
    </div>

    {{-- Upload & Restore --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-upload text-purple-500 mr-2"></i>Upload & Khôi phục
        </h3>
        
        <form id="uploadRestoreForm" action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file SQL</label>
                <input type="file" name="backup_file" accept=".sql" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <p class="font-semibold text-yellow-800">Cảnh báo:</p>
                        <p class="text-sm text-yellow-700">Chỉ upload file SQL backup hợp lệ. Dữ liệu hiện tại sẽ bị ghi đè!</p>
                    </div>
                </div>
            </div>

            <button type="button" onclick="confirmUploadRestore()" 
                    class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-file-upload mr-2"></i>Upload & Khôi phục
            </button>
        </form>
    </div>
</div>

{{-- Backup List --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800">Danh sách Backup</h3>
    </div>

    @if(count($backups) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Tên file</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Kích thước</th>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Ngày tạo</th>
                    <th class="text-center py-4 px-6 text-sm font-semibold text-gray-700">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($backups as $backup)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <i class="fas fa-database text-blue-500 mr-2"></i>
                            <span class="font-medium">{{ $backup['name'] }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="text-gray-600">{{ $backup['size'] }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="text-gray-600">{{ $backup['date'] }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('admin.backup.download', $backup['name']) }}" 
                               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition text-sm"
                               title="Tải xuống">
                                <i class="fas fa-download"></i>
                            </a>
                            
                            <form action="{{ route('admin.backup.delete', $backup['name']) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa backup này?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition text-sm"
                                        title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="py-12 text-center">
        <div class="text-gray-400">
            <i class="fas fa-inbox text-6xl mb-4"></i>
            <p class="text-lg">Chưa có backup nào</p>
            <p class="text-sm mt-2">Nhấn "Tạo Backup Mới" để tạo bản sao lưu đầu tiên</p>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function confirmRestore() {
    if (confirm('⚠️ CẢNH BÁO: Tất cả dữ liệu hiện tại sẽ bị ghi đè!\n\nBạn có chắc chắn muốn khôi phục dữ liệu từ backup này?')) {
        if (confirm('Xác nhận lần cuối: Dữ liệu hiện tại sẽ MẤT VĨNH VIỄN. Tiếp tục?')) {
            document.getElementById('restoreForm').submit();
        }
    }
}

function confirmUploadRestore() {
    const fileInput = document.querySelector('input[name="backup_file"]');
    if (!fileInput.files.length) {
        alert('Vui lòng chọn file backup!');
        return;
    }

    if (confirm('⚠️ CẢNH BÁO: Tất cả dữ liệu hiện tại sẽ bị ghi đè!\n\nBạn có chắc chắn muốn khôi phục từ file này?')) {
        if (confirm('Xác nhận lần cuối: Dữ liệu hiện tại sẽ MẤT VĨNH VIỄN. Tiếp tục?')) {
            document.getElementById('uploadRestoreForm').submit();
        }
    }
}
</script>
@endpush
