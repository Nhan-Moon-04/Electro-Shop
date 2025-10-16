@extends('layouts.app')

@section('title', 'Giỏ hàng - ElectroShop')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition"><i class="fas fa-home"></i></a></li>
                <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                <li><span class="text-gray-800 font-medium">Giỏ hàng</span></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    
    <!-- Shopping Cart Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Giỏ hàng của bạn</h1>
        <p class="text-gray-600">Bạn có <strong class="text-primary">3 sản phẩm</strong> trong giỏ hàng</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Cart Item 1 -->
            <div class="bg-white rounded-lg shadow-md p-4 flex items-center space-x-4">
                <input type="checkbox" checked class="w-5 h-5 text-primary rounded">
                <img src="https://via.placeholder.com/120x120/FFFFFF/0066CC?text=Product+1" alt="Product" class="w-24 h-24 object-cover rounded-lg">
                
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1 hover:text-primary cursor-pointer">
                        iPhone 15 Pro Max 256GB Titan Tự Nhiên
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">Màu: Titan Tự Nhiên | Dung lượng: 256GB</p>
                    <div class="flex items-center space-x-4">
                        <span class="text-red-600 font-bold text-lg">28.990.000₫</span>
                        <span class="text-gray-400 text-sm line-through">34.990.000₫</span>
                        <span class="badge-sale">-20%</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Quantity Control -->
                    <div class="flex items-center border-2 border-gray-300 rounded-lg">
                        <button class="px-3 py-2 hover:bg-gray-100 transition">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" value="1" min="1" class="w-12 text-center py-2 focus:outline-none">
                        <button class="px-3 py-2 hover:bg-gray-100 transition">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>

                    <!-- Remove Button -->
                    <button class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Cart Item 2 -->
            <div class="bg-white rounded-lg shadow-md p-4 flex items-center space-x-4">
                <input type="checkbox" checked class="w-5 h-5 text-primary rounded">
                <img src="https://via.placeholder.com/120x120/FFFFFF/0066CC?text=Product+2" alt="Product" class="w-24 h-24 object-cover rounded-lg">
                
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1 hover:text-primary cursor-pointer">
                        MacBook Pro 14" M3 Pro 18GB 512GB
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">Màu: Xám Không Gian | RAM: 18GB</p>
                    <div class="flex items-center space-x-4">
                        <span class="text-primary font-bold text-lg">52.990.000₫</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center border-2 border-gray-300 rounded-lg">
                        <button class="px-3 py-2 hover:bg-gray-100 transition">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" value="1" min="1" class="w-12 text-center py-2 focus:outline-none">
                        <button class="px-3 py-2 hover:bg-gray-100 transition">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>
                    <button class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Cart Item 3 -->
            <div class="bg-white rounded-lg shadow-md p-4 flex items-center space-x-4">
                <input type="checkbox" checked class="w-5 h-5 text-primary rounded">
                <img src="https://via.placeholder.com/120x120/FFFFFF/0066CC?text=Product+3" alt="Product" class="w-24 h-24 object-cover rounded-lg">
                
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1 hover:text-primary cursor-pointer">
                        AirPods Pro 2 (USB-C)
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">Bảo hành: 12 tháng chính hãng</p>
                    <div class="flex items-center space-x-4">
                        <span class="text-red-600 font-bold text-lg">5.990.000₫</span>
                        <span class="text-gray-400 text-sm line-through">6.990.000₫</span>
                        <span class="badge-sale">-15%</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center border-2 border-gray-300 rounded-lg">
                        <button class="px-3 py-2 hover:bg-gray-100 transition">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" value="2" min="1" class="w-12 text-center py-2 focus:outline-none">
                        <button class="px-3 py-2 hover:bg-gray-100 transition">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>
                    <button class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Select All & Delete -->
            <div class="bg-white rounded-lg shadow-md p-4 flex justify-between items-center">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" checked class="w-5 h-5 text-primary rounded mr-3">
                    <span class="font-medium">Chọn tất cả (3 sản phẩm)</span>
                </label>
                <button class="text-red-500 hover:text-red-700 font-medium transition">
                    <i class="fas fa-trash mr-2"></i>Xóa đã chọn
                </button>
            </div>

            <!-- Voucher Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center">
                    <i class="fas fa-ticket-alt text-primary mr-2"></i>Mã giảm giá
                </h3>
                <div class="flex space-x-3">
                    <input 
                        type="text" 
                        placeholder="Nhập mã giảm giá"
                        class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary transition"
                    >
                    <button class="btn-primary">Áp dụng</button>
                </div>
                
                <!-- Available Vouchers -->
                <div class="mt-4 space-y-2">
                    <div class="border-2 border-dashed border-primary rounded-lg p-3 flex items-center justify-between cursor-pointer hover:bg-primary/5 transition">
                        <div class="flex items-center space-x-3">
                            <div class="bg-primary text-white w-12 h-12 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-primary">GIAMGIA100K</p>
                                <p class="text-sm text-gray-600">Giảm 100K cho đơn từ 1 triệu</p>
                            </div>
                        </div>
                        <button class="text-primary font-medium hover:underline">Áp dụng</button>
                    </div>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gray-300 text-white w-12 h-12 rounded-lg flex items-center justify-center">
                                <i class="fas fa-percent"></i>
                            </div>
                            <div>
                                <p class="font-semibold">FREESHIP</p>
                                <p class="text-sm text-gray-600">Miễn phí vận chuyển đơn từ 500K</p>
                            </div>
                        </div>
                        <button class="text-primary font-medium hover:underline">Áp dụng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h3 class="font-bold text-xl mb-6">Thông tin đơn hàng</h3>
                
                <!-- Price Details -->
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-gray-700">
                        <span>Tạm tính (4 sản phẩm)</span>
                        <span class="font-semibold">99.960.000₫</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Giảm giá</span>
                        <span class="font-semibold text-red-600">-8.000.000₫</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Phí vận chuyển</span>
                        <span class="font-semibold text-green-600">Miễn phí</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Voucher giảm giá</span>
                        <span class="font-semibold text-red-600">-100.000₫</span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Total -->
                <div class="flex justify-between items-center mb-6">
                    <span class="text-lg font-bold">Tổng cộng</span>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-red-600">91.860.000₫</span>
                        <p class="text-sm text-gray-500">(Đã bao gồm VAT)</p>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button class="w-full btn-primary py-4 text-lg mb-3">
                    Tiến hành thanh toán
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>

                <a href="{{ route('products.index') }}" class="block w-full btn-outline text-center py-3">
                    <i class="fas fa-arrow-left mr-2"></i>Tiếp tục mua hàng
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
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-headset text-purple-500 mr-3"></i>
                        <span>Hỗ trợ 24/7</span>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-600 mb-3">Phương thức thanh toán</p>
                    <div class="flex flex-wrap gap-2">
                        <div class="border rounded px-2 py-1">
                            <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                        </div>
                        <div class="border rounded px-2 py-1">
                            <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                        </div>
                        <div class="border rounded px-2 py-1">
                            <i class="fas fa-credit-card text-2xl text-green-600"></i>
                        </div>
                        <div class="border rounded px-2 py-1 text-xs font-bold">
                            MOMO
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recently Viewed -->
    <section class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Có thể bạn quan tâm</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @for($i = 1; $i <= 5; $i++)
            <div class="product-card group">
                <div class="relative">
                    <img src="https://via.placeholder.com/300x300/FFFFFF/0066CC?text=Suggest+{{ $i }}" alt="Product" class="w-full h-48 object-cover">
                    <button class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-primary hover:text-white">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition">
                        Sản phẩm gợi ý số {{ $i }}
                    </h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">({{ rand(10, 200) }})</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-primary font-bold text-lg">{{ number_format(rand(5,15) * 990000, 0, ',', '.') }}₫</p>
                        </div>
                        <button class="bg-primary text-white w-10 h-10 rounded-lg hover:bg-primary-600 transition">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </section>
</div>

<!-- Empty Cart State (Hidden by default) -->
<div class="hidden container mx-auto px-4 py-16 text-center">
    <i class="fas fa-shopping-cart text-gray-300 text-9xl mb-6"></i>
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Giỏ hàng trống</h2>
    <p class="text-gray-600 mb-8">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
    <a href="{{ route('products.index') }}" class="inline-block btn-primary py-3 px-8 text-lg">
        <i class="fas fa-shopping-bag mr-2"></i>Tiếp tục mua sắm
    </a>
</div>

@endsection

@push('scripts')
<script>
    // Quantity controls
    document.querySelectorAll('.fa-minus').forEach(btn => {
        btn.closest('button').addEventListener('click', function() {
            const input = this.parentElement.querySelector('input[type="number"]');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateCart();
            }
        });
    });

    document.querySelectorAll('.fa-plus').forEach(btn => {
        btn.closest('button').addEventListener('click', function() {
            const input = this.parentElement.querySelector('input[type="number"]');
            const currentValue = parseInt(input.value);
            input.value = currentValue + 1;
            updateCart();
        });
    });

    // Remove item
    document.querySelectorAll('.fa-trash').forEach(btn => {
        btn.closest('button').addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                this.closest('.bg-white').remove();
                updateCart();
            }
        });
    });

    function updateCart() {
        // In production, this would make an AJAX call to update the cart
        console.log('Cart updated');
    }
</script>
@endpush
