@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - ElectroShop')

@section('content')

    <div class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition"><i
                                class="fas fa-home"></i></a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                    <li><a href="{{ route('account.orders') }}" class="text-gray-600 hover:text-primary transition">Đơn
                            hàng của tôi</a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                    <li><span class="text-gray-800 font-medium">Chi tiết đơn hàng</span></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center mb-6 pb-6 border-b">
                        <div class="w-24 h-24 rounded-full bg-primary/10 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-user text-primary text-4xl"></i>
                        </div>
                        <h3 class="font-bold text-lg" id="account-name">Đang tải...</h3>
                        <p class="text-gray-600 text-sm" id="account-email">Đang tải...</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="{{ route('account.profile') }}"
                            class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-user"></i>
                            <span class="font-medium">Thông tin tài khoản</span>
                        </a>
                        <a href="{{ route('account.orders') }}"
                            class="flex items-center space-x-3 px-4 py-3 bg-primary text-white rounded-lg">
                            <i class="fas fa-box"></i>
                            <span class="font-medium">Đơn hàng của tôi</span>
                        </a>
                        <a href="{{ route('account.favorites') }}"
                            class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-heart"></i>
                            <span class="font-medium">Sản phẩm yêu thích</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="lg:col-span-3">
                <div id="loading-state" class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i>
                    <p class="text-gray-600">Đang tải thông tin đơn hàng...</p>
                </div>

                <div id="error-state" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <i class="fas fa-exclamation-circle text-red-500 text-4xl mb-4"></i>
                        <p class="text-red-700 mb-4" id="error-message"></p>
                        <a href="{{ route('account.orders') }}"
                            class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition">
                            Quay lại danh sách đơn hàng
                        </a>
                    </div>
                </div>

                <div id="order-detail" class="hidden">
                    <!-- Order Header -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-bold mb-2">Đơn hàng <span id="order-id"></span></h2>
                                <p class="text-gray-600">Ngày đặt: <span id="order-date"></span></p>
                            </div>
                            <div class="text-right">
                                <span id="order-status-badge"
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold">
                                    <i id="order-status-icon" class="mr-2"></i>
                                    <span id="order-status-text"></span>
                                </span>
                            </div>
                        </div>

                        <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Info -->
                            <div>
                                <h3 class="font-semibold mb-3 flex items-center">
                                    <i class="fas fa-user-circle text-primary mr-2"></i>
                                    Thông tin người nhận
                                </h3>
                                <div class="space-y-2 text-gray-700">
                                    <p><strong>Họ tên:</strong> <span id="customer-name"></span></p>
                                    <p><strong>Điện thoại:</strong> <span id="customer-phone"></span></p>
                                    <p><strong>Địa chỉ:</strong> <span id="delivery-address"></span></p>
                                </div>
                            </div>

                            <!-- Order Info -->
                            <div>
                                <h3 class="font-semibold mb-3 flex items-center">
                                    <i class="fas fa-info-circle text-primary mr-2"></i>
                                    Thông tin đơn hàng
                                </h3>
                                <div class="space-y-2 text-gray-700">
                                    <p><strong>Phương thức thanh toán:</strong> <span id="payment-method"></span></p>
                                    <p><strong>Ghi chú:</strong> <span id="order-note"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products List -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h3 class="font-semibold text-lg mb-4 flex items-center">
                            <i class="fas fa-box-open text-primary mr-2"></i>
                            Sản phẩm trong đơn hàng
                        </h3>
                        <div id="products-list" class="space-y-4">
                            <!-- Products will be loaded here -->
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-semibold text-lg mb-4">Tổng cộng</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-700">
                                <span>Tạm tính:</span>
                                <span id="subtotal"></span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Giảm giá:</span>
                                <span id="discount" class="text-red-500"></span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-xl font-bold">
                                <span>Tổng cộng:</span>
                                <span class="text-primary" id="total-amount"></span>
                            </div>
                        </div>

                        <div class="mt-6 flex space-x-4">
                            <a href="{{ route('account.orders') }}"
                                class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition text-center">
                                <i class="fas fa-arrow-left mr-2"></i>Quay lại
                            </a>
                            <button id="cancel-order-btn"
                                class="flex-1 bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition hidden"
                                onclick="cancelOrder()">
                                <i class="fas fa-times-circle mr-2"></i>Hủy đơn hàng
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const orderId = new URLSearchParams(window.location.search).get('id');

        if (!orderId) {
            showError('Không tìm thấy mã đơn hàng');
        } else {
            loadOrderDetail();
        }

        function loadOrderDetail() {
            const token = localStorage.getItem('auth_token');

            // Load user info for sidebar
            fetch('/api/auth/me', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.user) {
                        document.getElementById('account-name').textContent = data.user.user_name;
                        document.getElementById('account-email').textContent = data.user.user_email;
                    } else if (data.user_name) {
                        document.getElementById('account-name').textContent = data.user_name;
                        document.getElementById('account-email').textContent = data.user_email;
                    }
                });

            // Load order detail
            fetch(`/api/orders/${orderId}`, {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.order) {
                        displayOrderDetail(data.order);
                    } else {
                        showError('Không tìm thấy thông tin đơn hàng');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Có lỗi xảy ra khi tải thông tin đơn hàng');
                });
        }

        function displayOrderDetail(order) {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('order-detail').classList.remove('hidden');

            // Order header
            document.getElementById('order-id').textContent = '#' + order.order_id;
            document.getElementById('order-date').textContent = formatDate(order.created_at);

            // Status badge
            const statusBadge = document.getElementById('order-status-badge');
            const statusIcon = document.getElementById('order-status-icon');
            const statusText = document.getElementById('order-status-text');

            statusBadge.className = 'inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold ' +
                getStatusClass(order.order_status);
            statusIcon.className = getStatusIcon(order.order_status) + ' mr-2';
            statusText.textContent = order.order_status;

            // Customer info
            document.getElementById('customer-name').textContent = order.customer_name;
            document.getElementById('customer-phone').textContent = order.customer_phone;
            document.getElementById('delivery-address').textContent = order.delivery_address;

            // Order info
            document.getElementById('payment-method').textContent = order.payment_method || 'COD';
            document.getElementById('order-note').textContent = order.order_note || 'Không có ghi chú';

            // Products list
            displayProducts(order.order_details);

            // Order summary
            const subtotal = order.order_details.reduce((sum, detail) => sum + (detail.unit_price * detail.quantity),
                0);
            const discount = order.discount_amount || 0;
            const total = order.order_total;

            document.getElementById('subtotal').textContent = formatPrice(subtotal) + 'đ';
            document.getElementById('discount').textContent = discount > 0 ? '-' + formatPrice(discount) + 'đ' : '0đ';
            document.getElementById('total-amount').textContent = formatPrice(total) + 'đ';

            // Show cancel button only if order is pending
            if (order.order_status === 'Chờ thanh toán') {
                document.getElementById('cancel-order-btn').classList.remove('hidden');
            }
        }

        function displayProducts(orderDetails) {
            const productsList = document.getElementById('products-list');
            let html = '';

            orderDetails.forEach(detail => {
                const product = detail.product_variant?.product;
                const variant = detail.product_variant;

                if (!product) return;

                html += `
                        <div class="flex border rounded-lg p-4 hover:shadow-md transition">
                            <img src="/imgs/${product.product_image}" 
                                 alt="${product.product_name}" 
                                 class="w-24 h-24 object-cover rounded-lg mr-4">
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg mb-1">
                                    <a href="/products/${product.product_id}" class="hover:text-primary transition">
                                        ${product.product_name}
                                    </a>
                                </h4>
                                ${variant ? `
                                    <p class="text-sm text-gray-600 mb-2">
                                        Phân loại: ${variant.variant_color} - ${variant.variant_memory}
                                    </p>
                                ` : ''}
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-gray-600">Số lượng: </span>
                                        <span class="font-semibold">${detail.quantity}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-primary">${formatPrice(detail.unit_price)}đ</p>
                                        <p class="text-sm text-gray-600">Tổng: ${formatPrice(detail.unit_price * detail.quantity)}đ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
            });

            productsList.innerHTML = html;
        }

        function showError(message) {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('error-state').classList.remove('hidden');
            document.getElementById('error-message').textContent = message;
        }

        function cancelOrder() {
            if (!confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
                return;
            }

            const token = localStorage.getItem('auth_token');
            fetch(`/api/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert('Đã hủy đơn hàng thành công');
                        window.location.reload();
                    } else {
                        alert('Có lỗi xảy ra khi hủy đơn hàng');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi hủy đơn hàng');
                });
        }

        function getStatusClass(status) {
            const classes = {
                'Chờ thanh toán': 'bg-yellow-100 text-yellow-800',
                'Đang giao hàng': 'bg-blue-100 text-blue-800',
                'Hoàn thành': 'bg-green-100 text-green-800',
                'Đã hủy': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function getStatusIcon(status) {
            const icons = {
                'Chờ thanh toán': 'fas fa-clock',
                'Đang giao hàng': 'fas fa-shipping-fast',
                'Hoàn thành': 'fas fa-check-circle',
                'Đã hủy': 'fas fa-times-circle'
            };
            return icons[status] || 'fas fa-info-circle';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN');
        }

        function formatPrice(price) {
            return Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    </script>
@endsection