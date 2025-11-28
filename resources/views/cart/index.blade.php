@extends('layouts.app')

@section('title', 'Giỏ hàng - ElectroShop')

@section('content')



    <div class="container mx-auto px-4 py-8">

        <!-- Shopping Cart Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">Giỏ hàng của bạn</h1>
            <p class="text-gray-600">Bạn có <strong class="text-primary" id="cart-item-count">0 sản phẩm</strong> trong giỏ
                hàng</p>
        </div>

        <!-- Loading State -->
        <div id="cart-loading" class="text-center py-12">
            <i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i>
            <p class="text-gray-600">Đang tải giỏ hàng...</p>
        </div>

        <!-- Empty Cart State -->
        <div id="cart-empty" style="display: none;" class="text-center py-16">
            <i class="fas fa-shopping-cart text-gray-300 text-9xl mb-6"></i>
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Giỏ hàng trống</h2>
            <p class="text-gray-600 mb-8">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
            <a href="{{ route('products.index') }}" class="inline-block btn-primary py-3 px-8 text-lg">
                <i class="fas fa-shopping-bag mr-2"></i>Tiếp tục mua sắm
            </a>
        </div>

        <!-- Cart Content -->
        <div id="cart-content" style="display: none;" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Select All -->
                <div class="bg-white rounded-lg shadow-md p-4 flex items-center justify-between mb-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" id="select-all-checkbox"
                            class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                            onchange="toggleSelectAll(this)">
                        <span class="ml-3 font-semibold">Chọn tất cả (<span id="selected-count">0</span>/<span
                                id="total-count">0</span>)</span>
                    </label>
                    <button class="text-red-500 hover:text-red-700 font-semibold" onclick="deleteSelected()">
                        <i class="fas fa-trash mr-2"></i>Xóa đã chọn
                    </button>
                </div>
                <div id="cart-items-list"></div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h3 class="font-bold text-xl mb-6">Thông tin đơn hàng</h3>

                    <!-- Price Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-700">
                            <span>Tạm tính</span>
                            <span class="font-semibold" id="cart-subtotal">0₫</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Phí vận chuyển</span>
                            <span class="font-semibold text-green-600">Miễn phí</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Total -->
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-bold">Tổng cộng</span>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-red-600" id="cart-total">0₫</span>
                            <p class="text-sm text-gray-500">(Đã bao gồm VAT)</p>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <button id="checkout-btn"
                        class="block w-full btn-primary py-4 text-lg mb-3 text-center disabled:opacity-50 disabled:cursor-not-allowed"
                        onclick="proceedToCheckout()" disabled>
                        Tiến hành thanh toán (<span id="checkout-count">0</span>)
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>

                    <a href="{{ route('products.index') }}" class="block w-full btn-outline text-center py-3">
                        <i class="fas fa-arrow-left mr-2"></i>Tiếp tục mua sắm
                    </a>

                    <!-- Trust Badges -->
                    <div class="mt-6 pt-6 border-t space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-shield-alt text-green-500 mr-3"></i>
                            <span>Thanh toán an toàn & bảo mật</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-truck text-blue-500 mr-3"></i>
                            <span>Miễn phí vận chuyển toàn quốc</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-undo text-orange-500 mr-3"></i>
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
        let cartData = [];

        function loadCart() {
            const token = localStorage.getItem('auth_token');

            if (!token) {
                document.getElementById('cart-loading').style.display = 'none';
                document.getElementById('cart-empty').style.display = 'block';
                return;
            }

            fetch('/api/cart', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    cartData = data.items || [];
                    document.getElementById('cart-loading').style.display = 'none';

                    if (cartData.length === 0) {
                        document.getElementById('cart-empty').style.display = 'block';
                    } else {
                        document.getElementById('cart-content').style.display = 'block';
                        renderCart(data);
                    }
                })
                .catch(error => {
                    console.error('Error loading cart:', error);
                    document.getElementById('cart-loading').style.display = 'none';
                    document.getElementById('cart-empty').style.display = 'block';
                });
        }

        function renderCart(data) {
            const itemsList = document.getElementById('cart-items-list');
            let html = '';

            data.items.forEach(item => {
                html += `
                                        <div class="bg-white rounded-lg shadow-md p-4 flex items-center gap-4">
                                            <input type="checkbox" class="item-checkbox w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" 
                                                   data-variant-id="${item.product_variant_id}" 
                                                   data-price="${item.price}" 
                                                   data-quantity="${item.quantity}"
                                                   onchange="updateSelectedItems()">

                                            <img src="/${item.image}" alt="${item.product_name}" 
                                                 class="w-24 h-24 object-cover rounded-lg"
                                                 onerror="this.src='/imgs/default.png'">

                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-800 mb-1 hover:text-primary cursor-pointer">
                                                    ${item.product_name}
                                                </h3>
                                                <p class="text-sm text-gray-500 mb-2">${item.variant_name}</p>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-red-600 font-bold text-lg">${formatPrice(item.price)}₫</span>
                                                    ${item.discount_percent > 0 ? `
                                                        <span class="text-gray-400 text-sm line-through">${formatPrice(item.original_price)}₫</span>
                                                        <span class="badge-sale">-${item.discount_percent}%</span>
                                                    ` : ''}
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <div class="flex items-center border-2 border-gray-300 rounded-lg">
                                                    <button class="px-3 py-2 hover:bg-gray-100 transition" onclick="updateQuantity(${item.product_variant_id}, ${item.quantity - 1})">
                                                        <i class="fas fa-minus text-sm"></i>
                                                    </button>
                                                    <input type="number" value="${item.quantity}" min="1" class="w-12 text-center py-2 focus:outline-none" readonly>
                                                    <button class="px-3 py-2 hover:bg-gray-100 transition" onclick="updateQuantity(${item.product_variant_id}, ${item.quantity + 1})">
                                                        <i class="fas fa-plus text-sm"></i>
                                                    </button>
                                                </div>
                                                <button class="text-red-500 hover:text-red-700 transition" onclick="removeItem(${item.product_variant_id})">
                                                    <i class="fas fa-trash text-xl"></i>
                                                </button>
                                            </div>
                                        </div>
                                    `;
            });

            itemsList.innerHTML = html;
            document.getElementById('cart-item-count').innerText = data.count + ' sản phẩm';
            document.getElementById('total-count').innerText = data.count;

            // Store total for later use
            window.cartTotalAmount = data.total;

            // Show total of all items initially
            document.getElementById('cart-subtotal').innerText = formatPrice(data.total) + '₫';
            document.getElementById('cart-total').innerText = formatPrice(data.total) + '₫';

            updateSelectedItems();
        } function formatPrice(price) {
            return Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function updateQuantity(variantId, newQuantity) {
            if (newQuantity < 1) {
                removeItem(variantId);
                return;
            }

            const token = localStorage.getItem('auth_token');
            fetch('/api/cart/update', {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_variant_id: variantId,
                    quantity: newQuantity
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCart();
                        if (typeof updateCartCount === 'function') {
                            updateCartCount();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function removeItem(variantId) {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                return;
            }

            const token = localStorage.getItem('auth_token');
            fetch('/api/cart/remove', {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_variant_id: variantId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCart();
                        if (typeof updateCartCount === 'function') {
                            updateCartCount();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function toggleSelectAll(checkbox) {
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            itemCheckboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
            updateSelectedItems();
        }

        function updateSelectedItems() {
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const checkoutBtn = document.getElementById('checkout-btn');

            // Update counts
            document.getElementById('selected-count').innerText = checkedBoxes.length;
            document.getElementById('checkout-count').innerText = checkedBoxes.length;

            // Update select all checkbox
            selectAllCheckbox.checked = checkedBoxes.length === itemCheckboxes.length && itemCheckboxes.length > 0;

            // Enable/disable checkout button
            checkoutBtn.disabled = checkedBoxes.length === 0;

            // Calculate total for selected items or show all items total
            let displayTotal;
            if (checkedBoxes.length > 0) {
                // Calculate selected items total
                displayTotal = 0;
                checkedBoxes.forEach(checkbox => {
                    const price = parseFloat(checkbox.dataset.price);
                    const quantity = parseInt(checkbox.dataset.quantity);
                    displayTotal += price * quantity;
                });
            } else {
                // Show all items total when nothing selected
                displayTotal = window.cartTotalAmount || 0;
            }

            document.getElementById('cart-subtotal').innerText = formatPrice(displayTotal) + '₫';
            document.getElementById('cart-total').innerText = formatPrice(displayTotal) + '₫';
        }

        function deleteSelected() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Vui lòng chọn sản phẩm cần xóa');
                return;
            }

            if (!confirm(`Bạn có chắc muốn xóa ${checkedBoxes.length} sản phẩm đã chọn?`)) {
                return;
            }

            const token = localStorage.getItem('auth_token');
            const deletePromises = [];

            checkedBoxes.forEach(checkbox => {
                const variantId = checkbox.dataset.variantId;
                deletePromises.push(
                    fetch('/api/cart/remove', {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_variant_id: parseInt(variantId) })
                    })
                );
            });

            Promise.all(deletePromises)
                .then(() => {
                    loadCart();
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function proceedToCheckout() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Vui lòng chọn sản phẩm để thanh toán');
                return;
            }

            const selectedItems = [];
            checkedBoxes.forEach(checkbox => {
                selectedItems.push(checkbox.dataset.variantId);
            });

            // Store selected items in sessionStorage to use in checkout page
            sessionStorage.setItem('checkout_items', JSON.stringify(selectedItems));
            window.location.href = '/checkout';
        }

        document.addEventListener('DOMContentLoaded', loadCart);
    </script>
@endpush