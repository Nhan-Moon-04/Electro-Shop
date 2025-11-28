@extends('layouts.app')

@section('title', $product->product_name . ' - ElectroShop')

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
                    <li><span class="text-gray-800 font-medium">{{ $product->product_name }}</span></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">

        <!-- Product Main Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Product Images -->
                @php
                    $totalImages = ($product->product_avt_img ? 1 : 0) + $product->images->count();

                    // Lấy variant mặc định
                    $selectedVariant = $product->variants->where('product_variant_is_display', 1)->first()
                        ?? $product->variants->first();

                    // Lấy discount từ variant
                    $activeDiscount = $selectedVariant && $selectedVariant->discount ? $selectedVariant->discount : null;
                @endphp
                <div x-data="{ currentImage: 0, images: {{ $totalImages }} }">
                    <!-- Main Image -->
                    <div class="relative mb-4 rounded-lg overflow-hidden bg-gray-100">
                        @if($activeDiscount)
                            <span class="absolute top-4 left-4 badge-sale z-10">-{{ $activeDiscount->discount_amount }}%</span>
                        @endif
                        @if($product->product_period == 'new')
                            <span class="absolute top-4 right-4 badge-new z-10">MỚI</span>
                        @endif

                        @if($product->product_avt_img || $product->images->isNotEmpty())
                            <!-- Main product image (product_avt_img) -->
                            @if($product->product_avt_img)
                                <img x-show="currentImage === 0"
                                    src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-96 object-contain"
                                    onerror="this.src='{{ asset('imgs/default.png') }}'">
                            @endif

                            <!-- Gallery images (image_name from product_imgs table) -->
                            @foreach($product->images as $index => $image)
                                <img x-show="currentImage === {{ $product->product_avt_img ? $index + 1 : $index }}"
                                    src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $image->image_name) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-96 object-contain"
                                    onerror="this.src='{{ asset('imgs/default.png') }}'">
                            @endforeach
                        @else
                            <img src="{{ asset('imgs/default.png') }}" alt="Chưa có ảnh"
                                class="w-full h-96 object-contain bg-gray-200">
                        @endif

                        <!-- Navigation Arrows -->
                        @if($totalImages > 1)
                            <button @click="currentImage = currentImage > 0 ? currentImage - 1 : images - 1"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center transition">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button @click="currentImage = currentImage < images - 1 ? currentImage + 1 : 0"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center transition">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Thumbnail Images -->
                    @if($totalImages > 1)
                        <div class="grid grid-cols-5 gap-2">
                            {{-- Main product image thumbnail (product_avt_img) --}}
                            @if($product->product_avt_img)
                                <button @click="currentImage = 0"
                                    :class="currentImage === 0 ? 'border-primary border-2' : 'border-gray-300 border'"
                                    class="rounded-lg overflow-hidden hover:border-primary transition">
                                    <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}"
                                        alt="Thumbnail" class="w-full h-20 object-cover"
                                        onerror="this.src='{{ asset('imgs/default.png') }}'">
                                </button>
                            @endif

                            {{-- Gallery thumbnails (image_name) --}}
                            @foreach($product->images as $index => $image)
                                <button @click="currentImage = {{ $product->product_avt_img ? $index + 1 : $index }}"
                                    :class="currentImage === {{ $product->product_avt_img ? $index + 1 : $index }} ? 'border-primary border-2' : 'border-gray-300 border'"
                                    class="rounded-lg overflow-hidden hover:border-primary transition">
                                    <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $image->image_name) }}"
                                        alt="Thumbnail" class="w-full h-20 object-cover"
                                        onerror="this.src='{{ asset('imgs/default.png') }}'">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->product_name }}</h1>

                    <!-- Rating & Brand -->
                    <div class="flex items-center space-x-4 mb-4 pb-4 border-b">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 text-lg mr-2">
                                @php
                                    $rating = $product->product_rate ?? 0;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $fullStars)
                                        <i class="fas fa-star"></i>
                                    @elseif($i == $fullStars + 1 && $hasHalfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-gray-600">({{ number_format($rating, 1) }} - {{ $reviewCount ?? 0 }} đánh
                                giá)</span>
                        </div>
                        <span class="text-gray-400">|</span>
                        <span class="text-gray-600"><strong>Thương hiệu:</strong>
                            @if($product->supplier)
                                <a href="#" class="text-primary hover:underline">{{ $product->supplier->supplier_name }}</a>
                            @else
                                <span>Chưa cập nhật</span>
                            @endif
                        </span>
                        <span class="text-gray-400">|</span>
                        <span class="text-gray-600">Đã bán:
                            <strong>{{ number_format($product->product_view_count ?? 0) }}</strong></span>
                    </div>

                    <!-- Price -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        @php
                            $originalPrice = $selectedVariant ? $selectedVariant->product_variant_price : 0;

                            // Tính giá sau giảm
                            $finalPrice = $originalPrice;
                            if ($activeDiscount) {
                                $finalPrice = $originalPrice - ($originalPrice * $activeDiscount->discount_amount / 100);
                            }
                        @endphp
                        <div class="flex items-baseline space-x-4 mb-2">
                            <span class="text-4xl font-bold text-red-600"
                                id="finalPrice">{{ number_format($finalPrice, 0, ',', '.') }}₫</span>
                            @if($activeDiscount)
                                <span class="text-xl text-gray-400 line-through"
                                    id="originalPrice">{{ number_format($originalPrice, 0, ',', '.') }}₫</span>
                                <span
                                    class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">-{{ $activeDiscount->discount_amount }}%</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">Giá đã bao gồm 10% VAT</p>
                    </div>

                    <!-- Product Variants -->
                    @if($product->variants->count() > 0)
                        <div class="mb-6"
                            x-data="{ selectedVariant: {{ $selectedVariant ? $selectedVariant->product_variant_id : 'null' }} }">
                            <label class="block font-semibold mb-3">Dung lượng:</label>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($product->variants as $variant)
                                    <button @click="selectedVariant = {{ $variant->product_variant_id }}"
                                        :class="selectedVariant === {{ $variant->product_variant_id }} ? 'border-primary bg-primary/10 text-primary' : 'border-gray-300'"
                                        class="border-2 rounded-lg py-2 px-4 font-medium hover:border-primary hover:text-primary transition variant-btn"
                                        data-variant-id="{{ $variant->product_variant_id }}"
                                        data-variant-price="{{ $variant->product_variant_price }}"
                                        data-variant-available="{{ $variant->product_variant_available }}"
                                        data-discount="{{ $variant->discount ? $variant->discount->discount_amount : 0 }}">
                                        {{ $variant->product_variant_name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity -->
                    <div class="mb-6">
                        <label class="block font-semibold mb-3">Số lượng:</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border-2 border-gray-300 rounded-lg">
                                <button id="minusBtn" class="px-4 py-2 hover:bg-gray-100 transition">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantityInput" value="1" min="1"
                                    max="{{ $selectedVariant ? $selectedVariant->product_variant_available : 999 }}"
                                    class="w-16 text-center py-2 focus:outline-none">
                                <button id="plusBtn" class="px-4 py-2 hover:bg-gray-100 transition">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <span class="text-gray-600">Còn <strong class="text-primary"
                                    id="availableQuantity">{{ $selectedVariant ? $selectedVariant->product_variant_available : 0 }}</strong>
                                sản phẩm</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <button id="addToCartBtn" class="btn-primary py-4 text-lg font-bold"
                            data-variant-id="{{ $selectedVariant ? $selectedVariant->product_variant_id : '' }}">
                            <i class="fas fa-shopping-cart mr-2"></i>Thêm vào giỏ hàng
                        </button>
                        <button id="buyNowBtn"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-lg transition text-lg"
                            data-variant-id="{{ $selectedVariant ? $selectedVariant->product_variant_id : '' }}">
                            <i class="fas fa-bolt mr-2"></i>Mua ngay
                        </button>
                    </div>

                    <div class="flex space-x-4 mb-6">
                        <button class="btn-outline py-3 px-6 flex-1">
                            <i class="fas fa-heart mr-2"></i>Yêu thích
                        </button>
                        <button class="btn-outline py-3 px-6 flex-1">
                            <i class="fas fa-share-alt mr-2"></i>Chia sẻ
                        </button>
                    </div>

                    <!-- Additional Info -->
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
                        Đánh giá ({{ $reviewCount ?? 0 }})
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" class="prose max-w-none">
                    {!! $product->product_description ?? '<p>Chưa có mô tả chi tiết cho sản phẩm này.</p>' !!}
                </div>

                <!-- Specifications Tab -->
                <div x-show="activeTab === 'specifications'">
                    <table class="w-full">
                        <tbody class="divide-y">
                            @if($product->details && $product->details->count() > 0)
                                @foreach($product->details as $detail)
                                    <tr>
                                        <td class="py-3 font-semibold bg-gray-50 px-4 w-1/3">{{ $detail->product_detail_name }}</td>
                                        <td class="py-3 px-4">{{ $detail->product_detail_value }} {{ $detail->product_detail_unit }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="py-3 px-4 text-center text-gray-500">Chưa có thông số kỹ thuật</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'">
                    <!-- Rating Summary -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-5xl font-bold text-primary mb-2">{{ number_format($rating ?? 0, 1) }}</div>
                                <div class="flex justify-center text-yellow-400 text-2xl mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($rating ?? 0))
                                            <i class="fas fa-star"></i>
                                        @elseif($i == floor($rating ?? 0) + 1 && (($rating ?? 0) - floor($rating ?? 0)) >= 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-gray-600">{{ $reviewCount ?? 0 }} đánh giá</p>
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="flex items-center">
                                        <span class="w-12">{{ $i }} <i class="fas fa-star text-yellow-400 text-sm"></i></span>
                                        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden mx-3">
                                            <div class="h-full bg-yellow-400" style="width: 0%"></div>
                                        </div>
                                        <span class="w-12 text-gray-600">0</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="text-center py-8 text-gray-500">
                        Chưa có đánh giá nào cho sản phẩm này
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <section>
                <h2 class="text-2xl font-bold mb-6">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="product-card group">
                            <div class="relative">
                                @if($relatedProduct->product_avt_img)
                                    <img src="{{ asset('imgs/product_image/P' . $relatedProduct->product_id . '/' . $relatedProduct->product_avt_img) }}"
                                        alt="{{ $relatedProduct->product_name }}" class="w-full h-48 object-cover"
                                        onerror="this.src='{{ asset('imgs/default.png') }}'">
                                @else
                                    <img src="{{ asset('imgs/default.png') }}" alt="No image" class="w-full h-48 object-cover">
                                @endif
                                <button
                                    class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-primary hover:text-white">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition">
                                    <a
                                        href="{{ route('products.show', $relatedProduct->product_id) }}">{{ $relatedProduct->product_name }}</a>
                                </h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">({{ $relatedProduct->product_rate ?? 0 }})</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        @php
                                            $variant = $relatedProduct->variants->first();
                                            $price = $variant ? $variant->product_variant_price : 0;
                                        @endphp
                                        <p class="text-primary font-bold text-lg">
                                            {{ number_format($price, 0, ',', '.') }}₫
                                        </p>
                                    </div>
                                    <button class="bg-primary text-white w-10 h-10 rounded-lg hover:bg-primary-600 transition">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        // Quantity controls
        const minusBtn = document.getElementById('minusBtn');
        const plusBtn = document.getElementById('plusBtn');
        const quantityInput = document.getElementById('quantityInput');

        if (minusBtn && plusBtn && quantityInput) {
            minusBtn.addEventListener('click', () => {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });

            plusBtn.addEventListener('click', () => {
                const currentValue = parseInt(quantityInput.value);
                const maxValue = parseInt(quantityInput.max);
                if (currentValue < maxValue) {
                    quantityInput.value = currentValue + 1;
                }
            });
        }

        // Update variant selection and price
        document.querySelectorAll('.variant-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const variantId = this.dataset.variantId;
                const variantPrice = parseFloat(this.dataset.variantPrice);
                const variantAvailable = this.dataset.variantAvailable;
                const discount = parseFloat(this.dataset.discount);

                // Calculate prices
                const originalPrice = variantPrice;
                const finalPrice = discount > 0 ? originalPrice - (originalPrice * discount / 100) : originalPrice;

                // Update price display
                const finalPriceEl = document.getElementById('finalPrice');
                const originalPriceEl = document.getElementById('originalPrice');

                if (finalPriceEl) {
                    finalPriceEl.textContent = new Intl.NumberFormat('vi-VN').format(finalPrice) + '₫';
                }

                if (originalPriceEl && discount > 0) {
                    originalPriceEl.textContent = new Intl.NumberFormat('vi-VN').format(originalPrice) + '₫';
                }

                // Update buttons
                document.getElementById('addToCartBtn').dataset.variantId = variantId;
                document.getElementById('buyNowBtn').dataset.variantId = variantId;

                // Update available quantity
                if (document.getElementById('availableQuantity')) {
                    document.getElementById('availableQuantity').textContent = variantAvailable;
                }

                // Update max quantity input
                if (quantityInput) {
                    quantityInput.max = variantAvailable;
                    if (parseInt(quantityInput.value) > parseInt(variantAvailable)) {
                        quantityInput.value = variantAvailable;
                    }
                }
            });
        });

        // Add to Cart functionality
        const addToCartBtn = document.getElementById('addToCartBtn');

        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function () {
                const token = localStorage.getItem('auth_token');

                if (!token) {
                    alert('Vui lòng đăng nhập để thêm vào giỏ hàng!');
                    window.location.href = '/login';
                    return;
                }

                const productVariantId = this.dataset.variantId;
                const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

                if (!productVariantId) {
                    alert('Vui lòng chọn phiên bản sản phẩm!');
                    return;
                }

                // Show loading state
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang thêm...';
                this.disabled = true;

                fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_variant_id: productVariantId,
                        quantity: quantity
                    })
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
                        if (!data) return;

                        if (data.success) {
                            this.innerHTML = '<i class="fas fa-check mr-2"></i>Đã thêm!';
                            this.classList.add('bg-green-500', 'hover:bg-green-600');
                            this.classList.remove('bg-primary', 'hover:bg-primary-600');

                            setTimeout(() => {
                                this.innerHTML = originalHTML;
                                this.classList.remove('bg-green-500', 'hover:bg-green-600');
                                this.classList.add('bg-primary', 'hover:bg-primary-600');
                                this.disabled = false;
                            }, 2000);

                            if (typeof updateCartCount === 'function') {
                                updateCartCount();
                            }
                        } else {
                            alert(data.message || data.error || 'Có lỗi xảy ra khi thêm vào giỏ hàng!');
                            this.innerHTML = originalHTML;
                            this.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to cart:', error);
                        alert('Không thể thêm vào giỏ hàng. Vui lòng thử lại!');
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                    });
            });
        }

        // Buy Now button functionality
        const buyNowBtn = document.getElementById('buyNowBtn');

        if (buyNowBtn) {
            buyNowBtn.addEventListener('click', function () {
                const token = localStorage.getItem('auth_token');

                if (!token) {
                    alert('Vui lòng đăng nhập để mua hàng!');
                    window.location.href = '/login';
                    return;
                }

                const productVariantId = this.dataset.variantId;
                const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

                if (!productVariantId) {
                    alert('Vui lòng chọn phiên bản sản phẩm!');
                    return;
                }

                // Redirect directly to checkout page with product info
                window.location.href = `/checkout?product_variant_id=${productVariantId}&quantity=${quantity}`;
            });
        }
    </script>
@endpush