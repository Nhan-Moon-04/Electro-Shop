@extends('layouts.app')

@section('title', 'Thanh toán - ElectroShop')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition"><i class="fas fa-home"></i></a></li>
                <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                <li><a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-primary transition">Giỏ hàng</a></li>
                <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                <li><span class="text-gray-800 font-medium">Thanh toán</span></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Progress Steps -->
<div class="bg-white border-b">
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-center space-x-4 md:space-x-8">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center">
                    <i class="fas fa-check"></i>
                </div>
                <span class="ml-2 font-medium hidden md:inline">Giỏ hàng</span>
            </div>
            <div class="w-16 h-1 bg-primary"></div>
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center">
                    2
                </div>
                <span class="ml-2 font-medium text-primary hidden md:inline">Thanh toán</span>
            </div>
            <div class="w-16 h-1 bg-gray-300"></div>
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center">
                    3
                </div>
                <span class="ml-2 font-medium text-gray-600 hidden md:inline">Hoàn tất</span>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-user text-primary mr-3"></i>
                        Thông tin người nhận
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name"
                                value="{{ old('name', Auth::user()->name ?? '') }}"
                                class="input-field"
                                placeholder="Nguyễn Văn A"
                                required
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                name="phone"
                                value="{{ old('phone') }}"
                                class="input-field"
                                placeholder="0912345678"
                                required
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email"
                                value="{{ old('email', Auth::user()->email ?? '') }}"
                                class="input-field"
                                placeholder="example@email.com"
                                required
                            >
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt text-primary mr-3"></i>
                        Địa chỉ giao hàng
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tỉnh/Thành phố <span class="text-red-500">*</span>
                            </label>
                            <select name="province" class="input-field" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                                <option value="hanoi">Hà Nội</option>
                                <option value="hcm">TP. Hồ Chí Minh</option>
                                <option value="danang">Đà Nẵng</option>
                                <option value="haiphong">Hải Phòng</option>
                                <option value="cantho">Cần Thơ</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Quận/Huyện <span class="text-red-500">*</span>
                            </label>
                            <select name="district" class="input-field" required>
                                <option value="">Chọn Quận/Huyện</option>
                                <option value="district1">Quận 1</option>
                                <option value="district2">Quận 2</option>
                                <option value="district3">Quận 3</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Phường/Xã <span class="text-red-500">*</span>
                            </label>
                            <select name="ward" class="input-field" required>
                                <option value="">Chọn Phường/Xã</option>
                                <option value="ward1">Phường 1</option>
                                <option value="ward2">Phường 2</option>
                                <option value="ward3">Phường 3</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Số nhà, tên đường <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="address"
                                value="{{ old('address') }}"
                                class="input-field"
                                placeholder="123 Đường ABC"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ghi chú đơn hàng (tùy chọn)
                        </label>
                        <textarea 
                            name="note"
                            rows="3"
                            class="input-field"
                            placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn..."
                        >{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-truck text-primary mr-3"></i>
                        Phương thức vận chuyển
                    </h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-4 border-2 border-primary bg-primary/5 rounded-lg cursor-pointer">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="express" checked class="mr-3">
                                <div>
                                    <p class="font-semibold">Giao hàng nhanh (1-2 giờ)</p>
                                    <p class="text-sm text-gray-600">Giao hàng trong ngày, áp dụng nội thành</p>
                                </div>
                            </div>
                            <span class="font-bold text-green-600">Miễn phí</span>
                        </label>
                        
                        <label class="flex items-center justify-between p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="standard" class="mr-3">
                                <div>
                                    <p class="font-semibold">Giao hàng tiêu chuẩn (2-3 ngày)</p>
                                    <p class="text-sm text-gray-600">Giao hàng toàn quốc</p>
                                </div>
                            </div>
                            <span class="font-bold text-green-600">Miễn phí</span>
                        </label>
                        
                        <label class="flex items-center justify-between p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="store" class="mr-3">
                                <div>
                                    <p class="font-semibold">Nhận tại cửa hàng</p>
                                    <p class="text-sm text-gray-600">Miễn phí - Nhận hàng sau 2 giờ</p>
                                </div>
                            </div>
                            <span class="font-bold text-green-600">Miễn phí</span>
                        </label>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-credit-card text-primary mr-3"></i>
                        Phương thức thanh toán
                    </h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-start p-4 border-2 border-primary bg-primary/5 rounded-lg cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" checked class="mt-1 mr-3">
                            <div class="flex-1">
                                <p class="font-semibold flex items-center">
                                    <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                                    Thanh toán khi nhận hàng (COD)
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Thanh toán bằng tiền mặt khi nhận hàng</p>
                            </div>
                        </label>
                        
                        <label class="flex items-start p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition">
                            <input type="radio" name="payment_method" value="bank" class="mt-1 mr-3">
                            <div class="flex-1">
                                <p class="font-semibold flex items-center">
                                    <i class="fas fa-university text-blue-600 mr-2"></i>
                                    Chuyển khoản ngân hàng
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Chuyển khoản trước, giao hàng sau</p>
                            </div>
                        </label>
                        
                        <label class="flex items-start p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition">
                            <input type="radio" name="payment_method" value="card" class="mt-1 mr-3">
                            <div class="flex-1">
                                <p class="font-semibold flex items-center">
                                    <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                                    Thanh toán bằng thẻ (ATM/Visa/Master)
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Thanh toán an toàn qua cổng VNPay</p>
                                <div class="flex space-x-2 mt-2">
                                    <i class="fab fa-cc-visa text-3xl text-blue-600"></i>
                                    <i class="fab fa-cc-mastercard text-3xl text-red-600"></i>
                                    <i class="fas fa-credit-card text-3xl text-green-600"></i>
                                </div>
                            </div>
                        </label>
                        
                        <label class="flex items-start p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition">
                            <input type="radio" name="payment_method" value="momo" class="mt-1 mr-3">
                            <div class="flex-1">
                                <p class="font-semibold flex items-center">
                                    <span class="text-pink-600 mr-2 font-bold">MOMO</span>
                                    Ví điện tử MoMo
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Thanh toán qua ví MoMo</p>
                            </div>
                        </label>
                        
                        <label class="flex items-start p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition">
                            <input type="radio" name="payment_method" value="installment" class="mt-1 mr-3">
                            <div class="flex-1">
                                <p class="font-semibold flex items-center">
                                    <i class="fas fa-percentage text-orange-600 mr-2"></i>
                                    Trả góp 0% lãi suất
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Áp dụng cho đơn hàng từ 3 triệu</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h3 class="font-bold text-xl mb-6">Đơn hàng của bạn</h3>
                    
                    <!-- Order Items -->
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                        <div class="flex items-center space-x-3 pb-4 border-b">
                            <img src="https://via.placeholder.com/60x60/FFFFFF/0066CC?text=P1" alt="Product" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-medium text-sm line-clamp-2">iPhone 15 Pro Max 256GB</h4>
                                <p class="text-sm text-gray-600">SL: 1</p>
                            </div>
                            <span class="font-semibold">28.990.000₫</span>
                        </div>
                        <div class="flex items-center space-x-3 pb-4 border-b">
                            <img src="https://via.placeholder.com/60x60/FFFFFF/0066CC?text=P2" alt="Product" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-medium text-sm line-clamp-2">MacBook Pro 14" M3 Pro</h4>
                                <p class="text-sm text-gray-600">SL: 1</p>
                            </div>
                            <span class="font-semibold">52.990.000₫</span>
                        </div>
                        <div class="flex items-center space-x-3 pb-4">
                            <img src="https://via.placeholder.com/60x60/FFFFFF/0066CC?text=P3" alt="Product" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-medium text-sm line-clamp-2">AirPods Pro 2 (USB-C)</h4>
                                <p class="text-sm text-gray-600">SL: 2</p>
                            </div>
                            <span class="font-semibold">11.980.000₫</span>
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-700">
                            <span>Tạm tính</span>
                            <span class="font-semibold">99.960.000₫</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Giảm giá sản phẩm</span>
                            <span class="font-semibold text-red-600">-8.000.000₫</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Voucher</span>
                            <span class="font-semibold text-red-600">-100.000₫</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Phí vận chuyển</span>
                            <span class="font-semibold text-green-600">Miễn phí</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Total -->
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-bold">Tổng thanh toán</span>
                        <span class="text-2xl font-bold text-red-600">91.860.000₫</span>
                    </div>

                    <!-- Terms -->
                    <label class="flex items-start mb-6 cursor-pointer">
                        <input type="checkbox" required class="mt-1 mr-3 w-5 h-5 text-primary rounded">
                        <span class="text-sm text-gray-600">
                            Tôi đã đọc và đồng ý với 
                            <a href="#" class="text-primary hover:underline">Điều khoản và Điều kiện</a> 
                            của ElectroShop
                        </span>
                    </label>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full btn-primary py-4 text-lg mb-3">
                        <i class="fas fa-check-circle mr-2"></i>Hoàn tất đặt hàng
                    </button>

                    <a href="{{ route('cart.index') }}" class="block w-full btn-outline text-center py-3">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại giỏ hàng
                    </a>

                    <!-- Security Badge -->
                    <div class="mt-6 pt-6 border-t text-center">
                        <i class="fas fa-lock text-green-500 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Thanh toán an toàn & bảo mật</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    // Update shipping cost based on method
    document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('Shipping method:', this.value);
            // Update shipping cost in real application
        });
    });

    // Update payment info based on method
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('Payment method:', this.value);
            // Show/hide additional payment info in real application
        });
    });
</script>
@endpush
