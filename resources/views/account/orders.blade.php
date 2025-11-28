@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - ElectroShop')

@section('content')

    <div class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition"><i
                                class="fas fa-home"></i></a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                    <li><span class="text-gray-800 font-medium">Đơn hàng của tôi</span></li>
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
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="font-medium">Sổ địa chỉ</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-heart"></i>
                            <span class="font-medium">Sản phẩm yêu thích</span>
                        </a>

                        <form id="account-logout-form">
                            <button type="submit"
                                class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-red-50 text-red-600 rounded-lg transition">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="font-medium">Đăng xuất</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-6">Đơn hàng của tôi</h2>

                    <!-- Order Filter Tabs -->
                    <div class="flex space-x-2 mb-6 border-b overflow-x-auto">
                        <button
                            class="order-filter-tab px-4 py-2 font-medium border-b-2 border-primary text-primary whitespace-nowrap"
                            data-status="all">
                            Tất cả
                        </button>
                        <button
                            class="order-filter-tab px-4 py-2 font-medium text-gray-600 hover:text-primary whitespace-nowrap"
                            data-status="Chờ thanh toán">
                            Chờ thanh toán
                        </button>
                        <button
                            class="order-filter-tab px-4 py-2 font-medium text-gray-600 hover:text-primary whitespace-nowrap"
                            data-status="Đang giao hàng">
                            Đang giao hàng
                        </button>
                        <button
                            class="order-filter-tab px-4 py-2 font-medium text-gray-600 hover:text-primary whitespace-nowrap"
                            data-status="Hoàn thành">
                            Hoàn thành
                        </button>
                        <button
                            class="order-filter-tab px-4 py-2 font-medium text-gray-600 hover:text-primary whitespace-nowrap"
                            data-status="Đã hủy">
                            Đã hủy
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div id="orders-loading" class="text-center py-12">
                        <i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i>
                        <p class="text-gray-600">Đang tải đơn hàng...</p>
                    </div>

                    <!-- Empty State -->
                    <div id="orders-empty" style="display: none;" class="text-center py-16">
                        <i class="fas fa-box-open text-gray-300 text-6xl mb-6"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Chưa có đơn hàng nào</h3>
                        <p class="text-gray-600 mb-8">Bạn chưa có đơn hàng nào trong hệ thống</p>
                        <a href="{{ route('products.index') }}" class="inline-block btn-primary py-3 px-8">
                            <i class="fas fa-shopping-bag mr-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>

                    <!-- Orders List -->
                    <div id="orders-list" style="display: none;" class="space-y-4">
                        <!-- Orders will be loaded here via JavaScript -->
                    </div>
                </div>
            </main>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let allOrders = [];
        let currentFilter = 'all';

        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('auth_token');

            if (!token) {
                alert('Vui lòng đăng nhập để xem đơn hàng!');
                window.location.href = '/login';
                return;
            }

            // Load user profile for sidebar
            loadUserProfile(token);

            // Load orders
            loadOrders(token);

            // Handle logout
            const logoutForm = document.getElementById('account-logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (typeof handleLogout === 'function') {
                        handleLogout();
                    } else {
                        localStorage.removeItem('auth_token');
                        window.location.href = '/login';
                    }
                });
            }

            // Handle filter tabs
            document.querySelectorAll('.order-filter-tab').forEach(tab => {
                tab.addEventListener('click', function () {
                    // Update active tab
                    document.querySelectorAll('.order-filter-tab').forEach(t => {
                        t.classList.remove('border-primary', 'text-primary', 'border-b-2');
                        t.classList.add('text-gray-600');
                    });
                    this.classList.add('border-primary', 'text-primary', 'border-b-2');
                    this.classList.remove('text-gray-600');

                    // Filter orders
                    currentFilter = this.dataset.status;
                    renderOrders();
                });
            });
        });

        function loadUserProfile(token) {
            fetch('/api/auth/me', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.name) {
                        document.getElementById('account-name').innerText = data.name;
                        document.getElementById('account-email').innerText = data.email;
                    }
                })
                .catch(error => console.error('Error loading profile:', error));
        }

        function loadOrders(token) {
            fetch('/api/orders/my-orders', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (response.status === 401) {
                        alert('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!');
                        localStorage.removeItem('auth_token');
                        window.location.href = '/login';
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('orders-loading').style.display = 'none';

                    if (!data || !data.orders || data.orders.length === 0) {
                        document.getElementById('orders-empty').style.display = 'block';
                    } else {
                        allOrders = data.orders;
                        document.getElementById('orders-list').style.display = 'block';
                        renderOrders();
                    }
                })
                .catch(error => {
                    console.error('Error loading orders:', error);
                    document.getElementById('orders-loading').style.display = 'none';
                    document.getElementById('orders-empty').style.display = 'block';
                });
        }

        function renderOrders() {
            const ordersList = document.getElementById('orders-list');
            let filteredOrders = allOrders;

            if (currentFilter !== 'all') {
                filteredOrders = allOrders.filter(order => order.order_status === currentFilter);
            }

            if (filteredOrders.length === 0) {
                ordersList.innerHTML = `
                        <div class="text-center py-8 text-gray-600">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p>Không có đơn hàng nào</p>
                        </div>
                    `;
                return;
            }

            let html = '';
            filteredOrders.forEach(order => {
                const statusClass = getStatusClass(order.order_status);
                const statusIcon = getStatusIcon(order.order_status);

                html += `
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-lg">Đơn hàng #${order.order_id}</h3>
                                    <p class="text-sm text-gray-600">Ngày đặt: ${formatDate(order.order_date)}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium ${statusClass}">
                                    <i class="${statusIcon} mr-1"></i>${order.order_status}
                                </span>
                            </div>

                            <div class="border-t pt-4">
                                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                    <div>
                                        <p class="text-gray-600">Người nhận:</p>
                                        <p class="font-medium">${order.order_name}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Số điện thoại:</p>
                                        <p class="font-medium">${order.order_phone}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-600">Địa chỉ giao hàng:</p>
                                        <p class="font-medium">${order.order_delivery_address}</p>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t">
                                    <div>
                                        <span class="text-gray-600">Tổng tiền:</span>
                                        <span class="text-xl font-bold text-primary ml-2">${formatPrice(order.order_total_after)}₫</span>
                                    </div>
                                    <div class="space-x-2">
                                        <a href="/account/order-detail?id=${order.order_id}" class="btn-outline inline-block px-4 py-2">
                                            <i class="fas fa-eye mr-1"></i>Chi tiết
                                        </a>
                                        ${order.order_status === 'Chờ thanh toán' ? `
                                            <button onclick="cancelOrder(${order.order_id})" class="btn-secondary px-4 py-2">
                                                <i class="fas fa-times mr-1"></i>Hủy đơn
                                            </button>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
            });

            ordersList.innerHTML = html;
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

        function cancelOrder(orderId) {
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
                    if (data.success) {
                        alert('Đã hủy đơn hàng thành công');
                        loadOrders(token);
                    } else {
                        alert(data.message || 'Không thể hủy đơn hàng');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi hủy đơn hàng');
                });
        }
    </script>
@endpush