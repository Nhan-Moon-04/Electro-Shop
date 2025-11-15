@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->order_id)

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Đơn hàng #{{ $order->order_id }}</h1>
                @php
                    $statusConfig = [
                        'Chờ thanh toán' => ['color' => 'yellow', 'icon' => 'fa-clock'],
                        'Đang giao hàng' => ['color' => 'blue', 'icon' => 'fa-shipping-fast'],
                        'Hoàn thành' => ['color' => 'green', 'icon' => 'fa-check-circle'],
                        'Đã hủy' => ['color' => 'red', 'icon' => 'fa-times-circle'],
                    ];
                    $status = $statusConfig[$order->order_status] ?? ['color' => 'gray', 'icon' => 'fa-question'];
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                    <i class="fas {{ $status['icon'] }} mr-2"></i>
                    {{ $order->order_status }}
                </span>
            </div>
            <p class="text-gray-600 mt-2">Đặt ngày {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.print', $order->order_id) }}" 
               target="_blank"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-print mr-2"></i>In đơn hàng
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Order Items --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Sản phẩm đã đặt</h2>
            <div class="space-y-4">
                @foreach($order->orderDetails as $detail)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                        @if($detail->productVariant && $detail->productVariant->product)
                            <img src="{{ asset('imgs/product_image/P' . $detail->productVariant->product->product_id . '/' . $detail->productVariant->product->product_avt_img) }}" 
                                 alt="{{ $detail->productVariant->product->product_name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.src='{{ asset('imgs/default.png') }}'">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800">
                            {{ $detail->productVariant->product->product_name ?? 'N/A' }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            @foreach($detail->productVariant->productDetails ?? [] as $pDetail)
                                {{ $pDetail->product_detail_name }}: {{ $pDetail->product_detail_value }}
                                @if(!$loop->last) | @endif
                            @endforeach
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Số lượng: {{ $detail->order_detail_quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">{{ number_format($detail->order_detail_price_after * $detail->order_detail_quantity, 0, ',', '.') }}₫</p>
                        @if($detail->order_detail_price_before != $detail->order_detail_price_after)
                        <p class="text-sm text-gray-500 line-through">{{ number_format($detail->order_detail_price_before * $detail->order_detail_quantity, 0, ',', '.') }}₫</p>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($detail->order_detail_price_after, 0, ',', '.') }}₫ / sp</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Delivery Address --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Địa chỉ giao hàng</h2>
                @if($order->order_status != 'Hoàn thành' && $order->order_status != 'Đã hủy')
                <button onclick="showEditAddressModal()" class="text-primary hover:text-primary-600 text-sm font-medium">
                    <i class="fas fa-edit mr-1"></i>Chỉnh sửa
                </button>
                @endif
            </div>
            <div class="space-y-2">
                <p class="text-gray-800">
                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                    <span id="delivery-address">{{ $order->order_delivery_address ?? 'Chưa cập nhật' }}</span>
                </p>
            </div>
        </div>

        {{-- Order Note --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Ghi chú đơn hàng</h2>
                @if($order->order_status != 'Hoàn thành' && $order->order_status != 'Đã hủy')
                <button onclick="showEditNoteModal()" class="text-primary hover:text-primary-600 text-sm font-medium">
                    <i class="fas fa-edit mr-1"></i>Chỉnh sửa
                </button>
                @endif
            </div>
            <p class="text-gray-600" id="order-note">{{ $order->order_note ?? 'Không có ghi chú' }}</p>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Update Status --}}
        @if($order->order_status != 'Hoàn thành' && $order->order_status != 'Đã hủy')
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Cập nhật trạng thái</h2>
            <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                @csrf
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="Chờ thanh toán" {{ $order->order_status == 'Chờ thanh toán' ? 'selected' : '' }}>Chờ thanh toán</option>
                    <option value="Đang giao hàng" {{ $order->order_status == 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="Hoàn thành" {{ $order->order_status == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="Đã hủy" {{ $order->order_status == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                </select>
                <button type="submit" class="w-full bg-primary hover:bg-primary-600 text-white py-3 rounded-lg font-medium transition">
                    <i class="fas fa-check mr-2"></i>Cập nhật trạng thái
                </button>
            </form>
        </div>
        @endif

        {{-- Customer Info --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Thông tin khách hàng</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Tên khách hàng</p>
                    <p class="font-medium text-gray-800">{{ $order->customer->customer_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Số điện thoại</p>
                    <p class="font-medium text-gray-800">{{ $order->order_phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-800">{{ $order->customer->user->user_email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Tóm tắt đơn hàng</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span class="font-medium">{{ number_format($order->order_total_before, 0, ',', '.') }}₫</span>
                </div>
                @if($order->order_total_before != $order->order_total_after)
                <div class="flex justify-between text-red-600">
                    <span>Giảm giá:</span>
                    <span class="font-medium">-{{ number_format($order->order_total_before - $order->order_total_after, 0, ',', '.') }}₫</span>
                </div>
                @endif
                <div class="border-t border-gray-200 pt-3 flex justify-between">
                    <span class="text-lg font-bold text-gray-800">Tổng cộng:</span>
                    <span class="text-lg font-bold text-primary">{{ number_format($order->order_total_after, 0, ',', '.') }}₫</span>
                </div>
                <div class="pt-3 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Phương thức thanh toán:</p>
                    <p class="font-medium">{{ $order->payingMethod->paying_method_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Trạng thái thanh toán:</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $order->order_paying_status == 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        <i class="fas {{ $order->order_paying_status == 1 ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                        {{ $order->order_paying_status == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Staff Info --}}
        @if($order->staff)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Nhân viên xử lý</h2>
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-primary text-xl"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $order->staff->user->user_login_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $order->staff->staff_role ?? 'Staff' }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Edit Address Modal --}}
<div id="editAddressModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Chỉnh sửa địa chỉ giao hàng</h3>
        <form action="{{ route('admin.orders.updateAddress', $order->order_id) }}" method="POST">
            @csrf
            <textarea name="delivery_address" rows="4" 
                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                      placeholder="Nhập địa chỉ giao hàng...">{{ $order->order_delivery_address }}</textarea>
            <div class="flex items-center space-x-3 mt-4">
                <button type="submit" class="flex-1 bg-primary hover:bg-primary-600 text-white py-2 rounded-lg transition">
                    Cập nhật
                </button>
                <button type="button" onclick="hideEditAddressModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg transition">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Note Modal --}}
<div id="editNoteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Chỉnh sửa ghi chú</h3>
        <form action="{{ route('admin.orders.updateNote', $order->order_id) }}" method="POST">
            @csrf
            <textarea name="note" rows="4" 
                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                      placeholder="Nhập ghi chú...">{{ $order->order_note }}</textarea>
            <div class="flex items-center space-x-3 mt-4">
                <button type="submit" class="flex-1 bg-primary hover:bg-primary-600 text-white py-2 rounded-lg transition">
                    Cập nhật
                </button>
                <button type="button" onclick="hideEditNoteModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg transition">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showEditAddressModal() {
        document.getElementById('editAddressModal').classList.remove('hidden');
    }

    function hideEditAddressModal() {
        document.getElementById('editAddressModal').classList.add('hidden');
    }

    function showEditNoteModal() {
        document.getElementById('editNoteModal').classList.remove('hidden');
    }

    function hideEditNoteModal() {
        document.getElementById('editNoteModal').classList.add('hidden');
    }
</script>
@endpush