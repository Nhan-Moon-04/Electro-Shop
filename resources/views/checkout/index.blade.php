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
                    <h2 class="text-xl font-bold mb-4">Thông tin đơn hàng</h2>

                    <!-- Single Product (luôn hiển thị vì đã bỏ giỏ hàng) -->
                    @if(isset($variant))
                        <div class="flex items-center space-x-4 p-4 border rounded-lg">
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                @if(isset($variant) && $variant->product && $variant->product->images->where('image_is_display', 1)->first())
                                    <img src="{{ $variant->product->images->where('image_is_display', 1)->first()->image_url ?? 'https://via.placeholder.com/80x80' }}"
                                        alt="Product" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold">{{ $variant->product->product_name ?? 'Sản phẩm' }}</h3>
                                <p class="text-gray-600">{{ $variant->product_variant_name ?? 'Phiên bản chuẩn' }}</p>
                                <p class="text-sm text-gray-500">Số lượng: {{ $quantity ?? 1 }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-primary">{{ number_format($variant->product_variant_price ?? 0) }}₫</p>
                            </div>
                        </div>
                    @endif

                    <!-- Giỏ hàng đã bị loại bỏ - chỉ hỗ trợ mua 1 sản phẩm -->
                </div>

                <!-- Customer Information Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">Thông tin giao hàng</h2>
                    <form id="checkoutForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên
                                    *</label>
                                <input type="text" id="customer_name" name="customer_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện
                                    thoại *</label>
                                <input type="tel" id="customer_phone" name="customer_phone" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="customer_email" name="customer_email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div class="mt-4">
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao
                                hàng *</label>
                            <textarea id="delivery_address" name="delivery_address" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                        </div>

                        <div class="mt-4">
                            <label for="order_note" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú đơn
                                hàng</label>
                            <textarea id="order_note" name="order_note" rows="2"
                                placeholder="Ví dụ: Giao hàng giờ hành chính, gọi trước 15 phút..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="product_variant_id" value="{{ $variant->product_variant_id ?? '' }}">
                        <input type="hidden" name="quantity" value="{{ $quantity ?? 1 }}">
                        <input type="hidden" name="type" value="single">
                    </form>
                </div>
            </div>


        </div>
    </div>
    </div>


    <!-- Right Column - Payment Methods -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
            <h2 class="text-xl font-bold mb-4">Phương thức thanh toán</h2>

            <!-- Payment Total -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($total_amount ?? 0) }}₫</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span>Phí vận chuyển:</span>
                    <span class="text-green-600">Miễn phí</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between items-center font-bold text-lg">
                    <span>Tổng cộng:</span>
                    <span class="text-primary">{{ number_format($total_amount ?? 0) }}₫</span>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="space-y-3 mb-6">
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="payment_method" value="vnpay" class="mr-3">
                    <div class="flex items-center">
                        <img src="https://via.placeholder.com/40x40/0066CC/FFFFFF?text=VP" alt="VNPay" class="w-8 h-8 mr-3">
                        <div>
                            <div class="font-semibold">VNPay QR Code</div>
                            <div class="text-sm text-gray-600">Quét mã QR VNPay để thanh toán</div>
                        </div>
                    </div>
                </label>

                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                    <div class="flex items-center">
                        <img src="https://via.placeholder.com/40x40/28A745/FFFFFF?text=QR" alt="QR" class="w-8 h-8 mr-3">
                        <div>
                            <div class="font-semibold">Chuyển khoản ngân hàng</div>
                            <div class="text-sm text-gray-600">Chuyển khoản qua QR Code</div>
                        </div>
                    </div>
                </label>

                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="payment_method" value="cash" class="mr-3">
                    <div class="flex items-center">
                        <img src="https://via.placeholder.com/40x40/FFC107/FFFFFF?text=💵" alt="COD" class="w-8 h-8 mr-3">
                        <div>
                            <div class="font-semibold">Thanh toán khi nhận hàng</div>
                            <div class="text-sm text-gray-600">Thanh toán bằng tiền mặt</div>
                        </div>
                    </div>
                </label>
            </div>





            <!-- VNPay Payment Section (hidden by default) -->
            <div id="vnpay-payment-section" class="mb-6">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <i class="fas fa-credit-card text-blue-500 text-3xl mb-2"></i>
                    <p class="text-sm text-gray-600">Bạn sẽ được chuyển đến trang thanh toán VNPay</p>
                </div>
            </div>

            <!-- Place Order Button -->
            <button id="placeOrderBtn"
                class="w-full bg-primary hover:bg-primary-600 text-white font-bold py-4 rounded-lg transition text-lg">
                <i class="fas fa-shopping-bag mr-2"></i>
                Đặt hàng
            </button>

            <!-- Additional Info -->
            <div class="mt-6 text-sm text-gray-600">
                <div class="flex items-center mb-2">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                    <span>Bảo mật thanh toán SSL</span>
                </div>
                <div class="flex items-center mb-2">
                    <i class="fas fa-truck text-green-500 mr-2"></i>
                    <span>Miễn phí vận chuyển toàn quốc</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-undo text-green-500 mr-2"></i>
                    <span>Đổi trả trong 7 ngày</span>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Order Summary Sidebar -->

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const qrSection = document.getElementById('qr-payment-section');
            const vnpaySection = document.getElementById('vnpay-payment-section');
            const placeOrderBtn = document.getElementById('placeOrderBtn');
            const checkoutForm = document.getElementById('checkoutForm');

            // Handle payment method selection
            paymentMethods.forEach(method => {
                method.addEventListener('change', function () {
                    // Hide all sections first
                    if (qrSection) qrSection.classList.add('hidden');
                    if (vnpaySection) vnpaySection.classList.add('hidden');

                    // When user selects payment method, only show the correct SECTION placeholder.
                    // We DO NOT auto-generate the QR here to avoid showing it unsolicited.
                    if (this.value === 'vnpay' || this.value === 'bank_transfer') {
                        // user will explicitly click "Quét mã QR để thanh toán" to generate and view the QR
                        if (qrSection) qrSection.classList.remove('hidden');
                        placeOrderBtn.innerHTML = '<i class="fas fa-qrcode mr-2"></i>Quét mã QR để thanh toán';
                    } else {
                        placeOrderBtn.innerHTML = '<i class="fas fa-shopping-bag mr-2"></i>Đặt hàng';
                    }
                });
            });

            // Add behavior for explicit QR button (do not auto-show on page load)
            const showQRBtn = document.getElementById('showQRBtn');
            if (showQRBtn) {
                showQRBtn.addEventListener('click', function () {
                    // generate and reveal QR when user explicitly requests it
                    generateQRCode();
                    if (qrSection) qrSection.classList.remove('hidden');
                    // also update button appearance
                    showQRBtn.classList.add('opacity-70');
                });
            }

            // Generate QR Code
            function generateQRCode() {
                const amount = {{ $total_amount ?? 0 }};
                const orderId = 'temp_' + Date.now();

                fetch('/vnpay/generate-qr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        amount: amount,
                        order_id: orderId
                    })
                })


                    .then(response => response.json())
                    .then(data => {
                        if (data.qr_url && document.getElementById('qr-code')) {
                            // Cập nhật QR Code
                            document.getElementById('qr-code').src = data.qr_url;

                            // Cập nhật thông tin ngân hàng từ API
                            if (data.bank_info) {
                                const bankInfo = data.bank_info;
                                document.getElementById('bank-name').textContent = bankInfo.bank_name;
                                document.getElementById('account-no').textContent = bankInfo.account_no;
                                document.getElementById('account-name').textContent = bankInfo.account_name;
                                document.getElementById('transfer-amount').textContent = bankInfo.amount + '₫';
                                document.getElementById('transfer-content').textContent = bankInfo.content;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error generating QR code:', error);
                        alert('Không thể tạo mã QR. Vui lòng thử lại!');
                    });
            }

            // Handle place order button click
            if (placeOrderBtn) {
                placeOrderBtn.addEventListener('click', function () {
                    if (!validateForm()) {
                        return;
                    }

                    const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
                    const formData = new FormData(checkoutForm);

                    // Create order first
                    createOrder(formData, selectedPayment);
                });
            }

            // Validate form
            function validateForm() {
                const name = document.getElementById('customer_name');
                const phone = document.getElementById('customer_phone');
                const address = document.getElementById('delivery_address');

                if (!name || !name.value.trim()) {
                    alert('Vui lòng nhập họ tên');
                    if (name) name.focus();
                    return false;
                }

                if (!phone || !phone.value.trim()) {
                    alert('Vui lòng nhập số điện thoại');
                    if (phone) phone.focus();
                    return false;
                }

                if (!address || !address.value.trim()) {
                    alert('Vui lòng nhập địa chỉ giao hàng');
                    if (address) address.focus();
                    return false;
                }

                return true;
            }

            // Create order
            async function createOrder(formData, paymentMethod) {
                placeOrderBtn.disabled = true;
                placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';

                try {
                    // Prepare order data
                    const orderData = {
                        customer_id: 1, // Default customer or from session
                        order_name: formData.get('customer_name'),
                        order_phone: formData.get('customer_phone'),
                        order_delivery_address: formData.get('delivery_address'),
                        order_note: formData.get('order_note') || '',
                        type: formData.get('type'),
                        paying_method_id: paymentMethod === 'vnpay' ? 1 : (paymentMethod === 'bank_transfer' ? 2 : 3)
                    };

                    // Add product data (luôn là single product vì đã bỏ giỏ hàng)
                    orderData.product_variant_id = formData.get('product_variant_id');
                    orderData.quantity = formData.get('quantity');

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
                        if (paymentMethod === 'vnpay' || paymentMethod === 'bank_transfer') {
                            // Show success message for QR payment (cả VNPay và Bank Transfer đều dùng QR)
                            alert('Đơn hàng đã được tạo! Vui lòng quét mã QR để thanh toán.');
                            window.location.href = '/payment/' + result.order_id;
                        } else {
                            // COD payment
                            alert('Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.');
                            window.location.href = '/payment/' + result.order_id;
                        }
                    } else {
                        throw new Error(result.error || 'Có lỗi xảy ra khi tạo đơn hàng');
                    }
                } catch (error) {
                    alert('Lỗi: ' + error.message);
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '<i class="fas fa-shopping-bag mr-2"></i>Đặt hàng';
                }
            }

            // Create VNPay payment
            async function createVNPayPayment(orderId) {
                try {
                    const response = await fetch('/vnpay/create-payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            amount: {{ $total_amount ?? 0 }}
                                                                                        })
                    });

                    const result = await response.json();

                    if (result.success && result.payment_url) {
                        // Redirect to VNPay
                        window.location.href = result.payment_url;
                    } else {
                        throw new Error('Không thể tạo link thanh toán VNPay');
                    }
                } catch (error) {
                    alert('Lỗi VNPay: ' + error.message);
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Thanh toán VNPay';
                }
            }
        });
    </script>
@endpush