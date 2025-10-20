@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - ElectroShop')

@section('content')

    <!-- Breadcrumb -->
    <div class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition"><i
                                class="fas fa-home"></i></a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-600 hover:text-primary transition">Sản
                            phẩm</a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                    <li><span class="text-gray-800 font-medium">iPhone 15 Pro Max 256GB</span></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">

        <!-- Product Main Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Product Images -->
                <div x-data="{ currentImage: 0, images: [1,2,3,4,5] }">
                    <!-- Main Image -->
                    <div class="relative mb-4 rounded-lg overflow-hidden bg-gray-100">
                        <span class="absolute top-4 left-4 badge-sale z-10">-20%</span>
                        <span class="absolute top-4 right-4 badge-new z-10">MỚI</span>
                        <template x-for="(image, index) in images" :key="index">
                            <img x-show="currentImage === index"
                                :src="'https://via.placeholder.com/600x600/FFFFFF/0066CC?text=Product+Image+' + (index + 1)"
                                alt="Product Image" class="w-full h-96 object-contain">
                        </template>

                        <!-- Navigation Arrows -->
                        <button @click="currentImage = currentImage > 0 ? currentImage - 1 : images.length - 1"
                            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center transition">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button @click="currentImage = currentImage < images.length - 1 ? currentImage + 1 : 0"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center transition">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Thumbnail Images -->
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="(image, index) in images" :key="index">
                            <button @click="currentImage = index"
                                :class="currentImage === index ? 'border-primary border-2' : 'border-gray-300 border'"
                                class="rounded-lg overflow-hidden hover:border-primary transition">
                                <img :src="'https://via.placeholder.com/150x150/FFFFFF/0066CC?text=Thumb+' + (index + 1)"
                                    alt="Thumbnail" class="w-full h-20 object-cover">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Product Details -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">iPhone 15 Pro Max 256GB Titan Tự Nhiên</h1>

                    <!-- Rating & Brand -->
                    <div class="flex items-center space-x-4 mb-4 pb-4 border-b">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 text-lg mr-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-600">(4.8 - 256 đánh giá)</span>
                        </div>
                        <span class="text-gray-400">|</span>
                        <span class="text-gray-600"><strong>Thương hiệu:</strong> <a href="#"
                                class="text-primary hover:underline">Apple</a></span>
                        <span class="text-gray-400">|</span>
                        <span class="text-gray-600">Đã bán: <strong>1,234</strong></span>
                    </div>

                    <!-- Price -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="flex items-baseline space-x-4 mb-2">
                            <span class="text-4xl font-bold text-red-600">28.990.000₫</span>
                            <span class="text-xl text-gray-400 line-through">34.990.000₫</span>
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">-20%</span>
                        </div>
                        <p class="text-sm text-gray-600">Giá đã bao gồm 10% VAT</p>
                    </div>

                    <!-- Product Variants -->
                    <div class="mb-6">
                        <label class="block font-semibold mb-3">Dung lượng:</label>
                        <div class="grid grid-cols-4 gap-3">
                            <button
                                class="border-2 border-primary bg-primary/10 text-primary rounded-lg py-2 px-4 font-medium hover:bg-primary hover:text-white transition">
                                128GB
                            </button>
                            <button
                                class="border-2 border-gray-300 rounded-lg py-2 px-4 font-medium hover:border-primary hover:text-primary transition">
                                256GB
                            </button>
                            <button
                                class="border-2 border-gray-300 rounded-lg py-2 px-4 font-medium hover:border-primary hover:text-primary transition">
                                512GB
                            </button>
                            <button
                                class="border-2 border-gray-300 rounded-lg py-2 px-4 font-medium hover:border-primary hover:text-primary transition">
                                1TB
                            </button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-semibold mb-3">Màu sắc:</label>
                        <div class="flex space-x-3">
                            <button class="w-12 h-12 rounded-full border-2 border-primary bg-gray-300 relative">
                                <i class="fas fa-check text-white absolute inset-0 flex items-center justify-center"></i>
                            </button>
                            <button
                                class="w-12 h-12 rounded-full border-2 border-gray-300 bg-black hover:border-primary transition"></button>
                            <button
                                class="w-12 h-12 rounded-full border-2 border-gray-300 bg-blue-900 hover:border-primary transition"></button>
                            <button
                                class="w-12 h-12 rounded-full border-2 border-gray-300 bg-white hover:border-primary transition"></button>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-6">
                        <label class="block font-semibold mb-3">Số lượng:</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border-2 border-gray-300 rounded-lg">
                                <button class="px-4 py-2 hover:bg-gray-100 transition">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" value="1" min="1" class="w-16 text-center py-2 focus:outline-none">
                                <button class="px-4 py-2 hover:bg-gray-100 transition">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <span class="text-gray-600">Còn <strong class="text-primary">48</strong> sản phẩm</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 mb-6">
                        <button class="btn-outline py-4 px-6">
                            <i class="fas fa-heart text-xl"></i>
                        </button>
                        <button class="btn-outline py-4 px-6">
                            <i class="fas fa-share-alt text-xl"></i>
                        </button>
                    </div>

                    <button id="buyNowBtn"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-lg transition text-lg">
                        <i class="fas fa-bolt mr-2"></i>Mua ngay - Giao hàng trong 1 giờ
                    </button> <!-- Additional Info -->
                    <div class="mt-6 bg-blue-50 rounded-lg p-4">
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Bảo hành chính hãng 12 tháng</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Hỗ trợ trả góp 0% lãi suất</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Miễn phí vận chuyển toàn quốc</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Đổi trả trong 7 ngày nếu có lỗi</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="bg-white rounded-lg shadow-md mb-8" x-data="{ activeTab: 'description' }">
            <!-- Tab Headers -->
            <div class="border-b">
                <div class="flex overflow-x-auto">
                    <button @click="activeTab = 'description'"
                        :class="activeTab === 'description' ? 'border-primary text-primary' : 'border-transparent text-gray-600'"
                        class="px-6 py-4 font-semibold border-b-2 whitespace-nowrap hover:text-primary transition">
                        Mô tả sản phẩm
                    </button>
                    <button @click="activeTab = 'specifications'"
                        :class="activeTab === 'specifications' ? 'border-primary text-primary' : 'border-transparent text-gray-600'"
                        class="px-6 py-4 font-semibold border-b-2 whitespace-nowrap hover:text-primary transition">
                        Thông số kỹ thuật
                    </button>
                    <button @click="activeTab = 'reviews'"
                        :class="activeTab === 'reviews' ? 'border-primary text-primary' : 'border-transparent text-gray-600'"
                        class="px-6 py-4 font-semibold border-b-2 whitespace-nowrap hover:text-primary transition">
                        Đánh giá (256)
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" class="prose max-w-none">
                    <h3 class="text-xl font-bold mb-4">Điểm nổi bật của iPhone 15 Pro Max</h3>
                    <p class="mb-4">iPhone 15 Pro Max là chiếc smartphone cao cấp nhất trong dòng iPhone 15 Series của
                        Apple. Với thiết kế khung titanium sang trọng, chip A17 Pro mạnh mẽ và camera 48MP ấn tượng.</p>

                    <h4 class="text-lg font-bold mb-2">Thiết kế đột phá với khung titanium</h4>
                    <p class="mb-4">Lần đầu tiên Apple sử dụng chất liệu titanium cấp hàng không cho khung máy, mang lại độ
                        bền cao nhưng vẫn giữ được trọng lượng nhẹ.</p>

                    <h4 class="text-lg font-bold mb-2">Hiệu năng đỉnh cao với chip A17 Pro</h4>
                    <p class="mb-4">Chip A17 Pro được sản xuất trên tiến trình 3nm tiên tiến nhất, mang lại hiệu năng vượt
                        trội và tiết kiệm điện năng tối ưu.</p>

                    <h4 class="text-lg font-bold mb-2">Camera chuyên nghiệp</h4>
                    <p class="mb-4">Hệ thống camera sau 3 ống kính với cảm biến chính 48MP, hỗ trợ zoom quang học 5x và
                        nhiều tính năng chụp ảnh chuyên nghiệp.</p>
                </div>

                <!-- Specifications Tab -->
                <div x-show="activeTab === 'specifications'">
                    <table class="w-full">
                        <tbody class="divide-y">
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">Màn hình</td>
                                <td class="py-3 px-4">6.7 inch, Super Retina XDR, 120Hz</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">Chip xử lý</td>
                                <td class="py-3 px-4">Apple A17 Pro (3nm)</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">RAM</td>
                                <td class="py-3 px-4">8GB</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">Bộ nhớ trong</td>
                                <td class="py-3 px-4">256GB / 512GB / 1TB</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">Camera sau</td>
                                <td class="py-3 px-4">48MP (chính) + 12MP (góc siêu rộng) + 12MP (telephoto 5x)</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">Camera trước</td>
                                <td class="py-3 px-4">12MP TrueDepth</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">Pin</td>
                                <td class="py-3 px-4">4422mAh, sạc nhanh 20W, sạc không dây 15W</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">Hệ điều hành</td>
                                <td class="py-3 px-4">iOS 17</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold bg-gray-50 px-4">Trọng lượng</td>
                                <td class="py-3 px-4">221g</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'">
                    <!-- Rating Summary -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-5xl font-bold text-primary mb-2">4.8</div>
                                <div class="flex justify-center text-yellow-400 text-2xl mb-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <p class="text-gray-600">256 đánh giá</p>
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="flex items-center">
                                        <span class="w-12">{{ $i }} <i class="fas fa-star text-yellow-400 text-sm"></i></span>
                                        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden mx-3">
                                            <div class="h-full bg-yellow-400" style="width: {{ rand(60, 95) }}%"></div>
                                        </div>
                                        <span class="w-12 text-gray-600">{{ rand(10, 150) }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="space-y-6">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="border-b pb-6">
                                <div class="flex items-start space-x-4">
                                    <img src="https://via.placeholder.com/60x60/0066CC/FFFFFF?text=U{{ $i }}" alt="User"
                                        class="w-12 h-12 rounded-full">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-semibold">Nguyễn Văn A</h4>
                                                <div class="flex text-yellow-400 text-sm">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ rand(1, 30) }} ngày trước</span>
                                        </div>
                                        <p class="text-gray-700 mb-3">Sản phẩm rất tốt, đúng như mô tả. Camera chụp ảnh đẹp, pin
                                            trâu. Shop giao hàng nhanh, đóng gói cẩn thận. Mình rất hài lòng!</p>
                                        <div class="flex space-x-2 mb-3">
                                            <img src="https://via.placeholder.com/100x100/FFFFFF/0066CC?text=Review"
                                                alt="Review"
                                                class="w-20 h-20 rounded-lg object-cover cursor-pointer hover:opacity-80">
                                            <img src="https://via.placeholder.com/100x100/FFFFFF/0066CC?text=Review"
                                                alt="Review"
                                                class="w-20 h-20 rounded-lg object-cover cursor-pointer hover:opacity-80">
                                            <img src="https://via.placeholder.com/100x100/FFFFFF/0066CC?text=Review"
                                                alt="Review"
                                                class="w-20 h-20 rounded-lg object-cover cursor-pointer hover:opacity-80">
                                        </div>
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <button class="hover:text-primary transition"><i
                                                    class="far fa-thumbs-up mr-1"></i>Hữu ích ({{ rand(5, 50) }})</button>
                                            <button class="hover:text-primary transition"><i class="far fa-comment mr-1"></i>Trả
                                                lời</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Load More -->
                    <div class="text-center mt-6">
                        <button class="btn-outline">Xem thêm đánh giá</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <section>
            <h2 class="text-2xl font-bold mb-6">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @for($i = 1; $i <= 5; $i++)
                    <div class="product-card group">
                        <div class="relative">
                            <img src="https://via.placeholder.com/300x300/FFFFFF/0066CC?text=Related+{{ $i }}" alt="Product"
                                class="w-full h-48 object-cover">
                            <button
                                class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-primary hover:text-white">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition">
                                Sản phẩm tương tự số {{ $i }}
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
                                    <p class="text-primary font-bold text-lg">
                                        {{ number_format(rand(20, 35) * 990000, 0, ',', '.') }}₫
                                    </p>
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

@endsection

@push('scripts')
    <script>
        // Quantity controls
        const minusBtn = document.querySelector('button:has(.fa-minus)');
        const plusBtn = document.querySelector('button:has(.fa-plus)');
        const quantityInput = document.querySelector('input[type="number"]');

        if (minusBtn && plusBtn && quantityInput) {
            minusBtn.addEventListener('click', () => {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });

            plusBtn.addEventListener('click', () => {
                const currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
            });
        }

        // Buy Now button functionality
        const buyNowBtn = document.getElementById('buyNowBtn');

        if (buyNowBtn) {
            buyNowBtn.addEventListener('click', function () {
                // Get product variant ID (you'll need to set this based on your product data)
                const productVariantId = 44; // You should get this dynamically from product data
                const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

                // Redirect to checkout with parameters
                const params = new URLSearchParams({
                    product_variant_id: productVariantId,
                    quantity: quantity
                });

                window.location.href = '/checkout?' + params.toString();
            });
        }

        // Giỏ hàng đã bị loại bỏ - chỉ còn chức năng "Mua ngay"
    </script>
@endpush