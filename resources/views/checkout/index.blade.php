@extends('layouts.app')

@section('title', 'Thanh Toán - ElectroShop')

@section('content')

    <!-- Breadcrumb -->
    <div class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition"><i
                                class="fas fa-home"></i></a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                    <li><span class="text-gray-800 font-medium">Thanh toán</span></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column - Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-shopping-cart mr-2 text-primary"></i>
                        Thông tin đơn hàng
                    </h2>

                    <!-- Single Product (luôn hiển thị vì đã bỏ giỏ hàng) -->
                    @if(isset($variant))
                        <div class="flex items-center space-x-4 p-4 border rounded-lg bg-gray-50">
                            <div class="w-24 h-24 bg-white rounded-lg overflow-hidden shadow-sm">
                                @if($variant->product)
                                    @php
                                        $productImage = $variant->product->getFirstImage();
                                    @endphp
                                    <img src="{{ $productImage }}" alt="{{ $variant->product->product_name }}"
                                        class="w-full h-full object-cover" onerror="this.src='{{ asset('imgs/default.png') }}'">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">{{ $variant->product->product_name ?? 'Sản phẩm' }}</h3>
                                <p class="text-gray-600 mb-1">
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ $variant->product_variant_name ?? 'Phiên bản chuẩn' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-cube mr-1"></i>
                                    Số lượng: <span class="font-medium">{{ $quantity ?? 1 }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-primary">
                                    {{ number_format($variant->product_variant_price ?? 0) }}₫</p>
                                <p class="text-sm text-gray-500">Giá / sản phẩm</p>
                            </div>
                        </div>
                    @endif

                    <!-- Giỏ hàng đã bị loại bỏ - chỉ hỗ trợ mua 1 sản phẩm -->
                </div>

                <!-- Customer Information Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-primary"></i>
                        Thông tin giao hàng
                    </h2>
                    <form id="checkoutForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-alt mr-1"></i>
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="customer_name" name="customer_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    placeholder="Nhập họ và tên">
                            </div>
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-1"></i>
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="customer_phone" name="customer_phone" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    placeholder="Nhập số điện thoại">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-1"></i>
                                Email
                            </label>
                            <input type="email" id="customer_email" name="customer_email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="example@email.com (tùy chọn)">
                        </div>

                        <div class="mt-4">
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                Địa chỉ giao hàng <span class="text-red-500">*</span>
                            </label>
                            <textarea id="delivery_address" name="delivery_address" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Nhập địa chỉ đầy đủ (số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố)"></textarea>
                        </div>

                        <div class="mt-4">
                            <label for="order_note" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment-alt mr-1"></i>
                                Ghi chú đơn hàng
                            </label>
                            <textarea id="order_note" name="order_note" rows="2"
                                placeholder="Ví dụ: Giao hàng giờ hành chính, gọi trước 15 phút..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"></textarea>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="product_variant_id" value="{{ $variant->product_variant_id ?? '' }}">
                        <input type="hidden" name="quantity" value="{{ $quantity ?? 1 }}">
                        <input type="hidden" name="type" value="single">
                    </form>
                </div>
            </div>


            <!-- Right Column - Payment Methods -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-credit-card mr-2 text-primary"></i>
                        Phương thức thanh toán
                    </h2>

                    <!-- Payment Total -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-4 mb-6 border">
                        <div class="flex justify-between items-center mb-2">
                            <span class="flex items-center">
                                <i class="fas fa-calculator mr-2 text-gray-600"></i>
                                Tạm tính:
                            </span>
                            <span class="font-medium">{{ number_format($total_amount ?? 0) }}₫</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="flex items-center">
                                <i class="fas fa-shipping-fast mr-2 text-green-600"></i>
                                Phí vận chuyển:
                            </span>
                            <span class="text-green-600 font-medium">Miễn phí</span>
                        </div>
                        <hr class="my-3 border-gray-300">
                        <div class="flex justify-between items-center font-bold text-lg">
                            <span class="flex items-center">
                                <i class="fas fa-money-bill-wave mr-2 text-primary"></i>
                                Tổng cộng:
                            </span>
                            <span class="text-primary text-xl">{{ number_format($total_amount ?? 0) }}₫</span>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="space-y-3 mb-6">
                        <label
                            class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition duration-200">
                            <input type="radio" name="payment_method" value="vnpay" class="mr-3 text-blue-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-qrcode text-white"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">VNPay QR Code</div>
                                    <div class="text-sm text-gray-600">Quét mã QR VNPay để thanh toán</div>
                                </div>
                            </div>
                        </label>

                        <label
                            class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-green-50 hover:border-green-300 transition duration-200">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3 text-green-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-university text-white"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">Chuyển khoản ngân hàng</div>
                                    <div class="text-sm text-gray-600">Chuyển khoản qua QR Code</div>
                                </div>
                            </div>
                        </label>

                        <label
                            class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-yellow-50 hover:border-yellow-300 transition duration-200">
                            <input type="radio" name="payment_method" value="cash" class="mr-3 text-yellow-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-money-bill-alt text-white"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">Thanh toán khi nhận hàng</div>
                                    <div class="text-sm text-gray-600">Thanh toán bằng tiền mặt</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- VNPay Payment Section (hidden by default) -->
                    <div id="vnpay-payment-section" class="mb-6 hidden">
                        <div class="bg-blue-50 p-4 rounded-lg text-center border border-blue-200">
                            <i class="fas fa-credit-card text-blue-500 text-3xl mb-2"></i>
                            <p class="text-sm text-gray-600">Bạn sẽ được chuyển đến trang thanh toán VNPay</p>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button id="placeOrderBtn"
                        class="w-full bg-gradient-to-r from-primary to-blue-600 hover:from-primary-700 hover:to-blue-700 text-white font-bold py-4 rounded-lg transition duration-200 text-lg shadow-lg transform hover:scale-105">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Đặt hàng
                    </button>

                    <!-- Additional Info -->
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-shield-alt text-green-500"></i>
                            </div>
                            <span>Bảo mật thanh toán SSL</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-truck text-green-500"></i>
                            </div>
                            <span>Miễn phí vận chuyển toàn quốc</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-undo text-green-500"></i>
                            </div>
                            <span>Đổi trả trong 7 ngày</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const qrSection = document.getElementById('qr-payment-section');
            const vnpaySection = document.getElementById('vnpay-payment-section');
            const placeOrderBtn = document.getElementById('placeOrderBtn');
            const checkoutForm = document.getElementById('checkoutForm');

            // Handle payment method selection with visual feedback
            paymentMethods.forEach(method => {
                method.addEventListener('change', function () {
                    // Remove selected styling from all labels
                    paymentMethods.forEach(pm => {
                        const label = pm.closest('label');
                        label.classList.remove('border-blue-500', 'bg-blue-50', 'border-green-500', 'bg-green-50', 'border-yellow-500', 'bg-yellow-50');
                    });

                    // Add selected styling to current selection
                    const currentLabel = this.closest('label');
                    if (this.value === 'vnpay') {
                        currentLabel.classList.add('border-blue-500', 'bg-blue-50');
                        if (vnpaySection) vnpaySection.classList.remove('hidden');
                        placeOrderBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Thanh toán VNPay';
                    } else if (this.value === 'bank_transfer') {
                        currentLabel.classList.add('border-green-500', 'bg-green-50');
                        if (qrSection) qrSection.classList.remove('hidden');
                        placeOrderBtn.innerHTML = '<i class="fas fa-qrcode mr-2"></i>Quét mã QR để thanh toán';
                    } else {
                        currentLabel.classList.add('border-yellow-500', 'bg-yellow-50');
                        placeOrderBtn.innerHTML = '<i class="fas fa-money-bill-alt mr-2"></i>Đặt hàng - Thanh toán khi nhận';
                    }

                    // Hide unused sections
                    if (this.value !== 'vnpay' && vnpaySection) vnpaySection.classList.add('hidden');
                    if (this.value !== 'bank_transfer' && qrSection) qrSection.classList.add('hidden');
                });
            });

            // Add input validation feedback
            const requiredInputs = document.querySelectorAll('input[required], textarea[required]');
            requiredInputs.forEach(input => {
                input.addEventListener('blur', function () {
                    if (this.value.trim() === '') {
                        this.classList.add('border-red-500');
                        this.classList.remove('border-gray-300');
                    } else {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                        setTimeout(() => {
                            this.classList.remove('border-green-500');
                            this.classList.add('border-gray-300');
                        }, 2000);
                    }
                });
            });

            // Handle place order button click
            if (placeOrderBtn) {
                placeOrderBtn.addEventListener('click', function () {
                    if (!validateForm()) {
                        return;
                    }

                    const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
                    if (!selectedPaymentMethod) {
                        alert('Vui lòng chọn phương thức thanh toán');
                        return;
                    }

                    const formData = new FormData(checkoutForm);
                    createOrder(formData, selectedPaymentMethod.value);
                });
            }

            // Enhanced form validation with better UX
            function validateForm() {
                const name = document.getElementById('customer_name');
                const phone = document.getElementById('customer_phone');
                const address = document.getElementById('delivery_address');
                let isValid = true;

                // Reset previous error states
                requiredInputs.forEach(input => {
                    input.classList.remove('border-red-500');
                });

                if (!name || !name.value.trim()) {
                    showFieldError(name, 'Vui lòng nhập họ tên');
                    isValid = false;
                }

                if (!phone || !phone.value.trim()) {
                    showFieldError(phone, 'Vui lòng nhập số điện thoại');
                    isValid = false;
                } else if (!/^[0-9]{10,11}$/.test(phone.value.trim())) {
                    showFieldError(phone, 'Số điện thoại không hợp lệ (10-11 số)');
                    isValid = false;
                }

                if (!address || !address.value.trim()) {
                    showFieldError(address, 'Vui lòng nhập địa chỉ giao hàng');
                    isValid = false;
                }

                return isValid;
            }

            function showFieldError(field, message) {
                field.classList.add('border-red-500');
                field.focus();

                // Create or update error message
                let errorDiv = field.parentNode.querySelector('.error-message');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                    field.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = message;

                // Remove error after a few seconds
                setTimeout(() => {
                    field.classList.remove('border-red-500');
                    if (errorDiv) errorDiv.remove();
                }, 3000);
            }

            // Create order with better loading state
            async function createOrder(formData, paymentMethod) {
                // Disable button and show loading state
                placeOrderBtn.disabled = true;
                placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
                placeOrderBtn.classList.add('opacity-75');

                try {
                    const orderData = {
                        customer_id: 1,
                        order_name: formData.get('customer_name'),
                        order_phone: formData.get('customer_phone'),
                        order_delivery_address: formData.get('delivery_address'),
                        order_note: formData.get('order_note') || '',
                        type: formData.get('type'),
                        paying_method_id: paymentMethod === 'vnpay' ? 1 : (paymentMethod === 'bank_transfer' ? 2 : 3),
                        product_variant_id: formData.get('product_variant_id'),
                        quantity: formData.get('quantity')
                    };

                    const response = await fetch('/create-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(orderData)
                    });

                    const result = await response.json();

                    if (response.ok && result.order_id) {
                        // Success animation
                        placeOrderBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Thành công!';
                        placeOrderBtn.classList.add('bg-green-500');

                        setTimeout(() => {
                            if (paymentMethod === 'vnpay' || paymentMethod === 'bank_transfer') {
                                alert('Đơn hàng đã được tạo! Vui lòng thanh toán để hoàn tất.');
                            } else {
                                alert('Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.');
                            }
                            window.location.href = '/payment/' + result.order_id;
                        }, 1000);
                    } else {
                        throw new Error(result.error || 'Có lỗi xảy ra khi tạo đơn hàng');
                    }
                } catch (error) {
                    alert('Lỗi: ' + error.message);
                    resetButton();
                }
            }

            function resetButton() {
                placeOrderBtn.disabled = false;
                placeOrderBtn.classList.remove('opacity-75', 'bg-green-500');

                const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
                if (selectedPayment) {
                    if (selectedPayment.value === 'vnpay') {
                        placeOrderBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Thanh toán VNPay';
                    } else if (selectedPayment.value === 'bank_transfer') {
                        placeOrderBtn.innerHTML = '<i class="fas fa-qrcode mr-2"></i>Quét mã QR để thanh toán';
                    } else {
                        placeOrderBtn.innerHTML = '<i class="fas fa-money-bill-alt mr-2"></i>Đặt hàng - Thanh toán khi nhận';
                    }
                } else {
                    placeOrderBtn.innerHTML = '<i class="fas fa-shopping-bag mr-2"></i>Đặt hàng';
                }
            }
        });
    </script>
@endpush