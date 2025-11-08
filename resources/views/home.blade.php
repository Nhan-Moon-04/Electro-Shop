@extends('layouts.app')

@section('title', 'ElectroShop - Siêu thị điện máy hàng đầu Việt Nam')

@section('content')

    <!-- Hero Banner Slider -->
    <section class="relative bg-gradient-to-r from-primary to-primary-700 text-white overflow-hidden"
        x-data="{ currentSlide: 0, slides: 3 }"
        x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 5000)">
        <div class="container mx-auto px-4 py-16 md:py-24">
            <!-- Slide 1 -->
            <div x-show="currentSlide === 0" x-transition class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <span
                        class="inline-block bg-yellow-400 text-gray-900 px-4 py-1 rounded-full text-sm font-bold mb-4">KHUYẾN
                        MÃI HOT</span>
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Giảm đến 50%</h1>
                    <p class="text-xl mb-6">Cho tất cả sản phẩm điện tử cao cấp</p>
                    <a href="{{ route('products.index') }}"
                        class="inline-block bg-white text-primary px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
                        Mua ngay <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('imgs/banner/bannerHot.png') }}" alt="Banner" class="w-full rounded-lg shadow-2xl">
                </div>
            </div>

            <!-- Slide 2 -->
            <div x-show="currentSlide === 1" x-transition class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="inline-block bg-red-500 text-white px-4 py-1 rounded-full text-sm font-bold mb-4">FLASH
                        SALE</span>
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Laptop Gaming</h1>
                    <p class="text-xl mb-6">Giảm sốc lên đến 40% - Số lượng có hạn</p>
                    <a href="{{ route('products.index') }}"
                        class="inline-block bg-white text-primary px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
                        Xem ngay <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('imgs/banner/bannerNew.png') }}" alt="Banner" class="w-full rounded-lg shadow-2xl">
                </div>
            </div>

            <!-- Slide 3 -->
            <div x-show="currentSlide === 2" x-transition class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="inline-block bg-green-500 text-white px-4 py-1 rounded-full text-sm font-bold mb-4">MỚI
                        VỀ</span>
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">iPhone 15 Pro Max</h1>
                    <p class="text-xl mb-6">Công nghệ tiên tiến - Thiết kế đột phá</p>
                    <a href="{{ route('products.index') }}"
                        class="inline-block bg-white text-primary px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
                        Khám phá <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('imgs/default.png') }}" alt="Banner" class="w-full rounded-lg shadow-2xl">
                </div>
            </div>
        </div>

        <!-- Slider Indicators -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button @click="currentSlide = 0" :class="currentSlide === 0 ? 'bg-white' : 'bg-white/50'"
                class="w-3 h-3 rounded-full transition"></button>
            <button @click="currentSlide = 1" :class="currentSlide === 1 ? 'bg-white' : 'bg-white/50'"
                class="w-3 h-3 rounded-full transition"></button>
            <button @click="currentSlide = 2" :class="currentSlide === 2 ? 'bg-white' : 'bg-white/50'"
                class="w-3 h-3 rounded-full transition"></button>
        </div>
    </section>

    <!-- Features -->
    <section class="py-8 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-shipping-fast text-4xl text-primary"></i>
                    <div>
                        <h4 class="font-bold">Miễn phí vận chuyển</h4>
                        <p class="text-sm text-gray-600">Đơn hàng trên 500K</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="fas fa-shield-alt text-4xl text-primary"></i>
                    <div>
                        <h4 class="font-bold">Bảo hành chính hãng</h4>
                        <p class="text-sm text-gray-600">Lên đến 24 tháng</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="fas fa-undo text-4xl text-primary"></i>
                    <div>
                        <h4 class="font-bold">Đổi trả dễ dàng</h4>
                        <p class="text-sm text-gray-600">Trong vòng 7 ngày</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="fas fa-headset text-4xl text-primary"></i>
                    <div>
                        <h4 class="font-bold">Hỗ trợ 24/7</h4>
                        <p class="text-sm text-gray-600">Hotline: 1900 1234</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Danh mục nổi bật</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('products.category', $category->category_id) }}" class="group">
                        <div class="card p-6 text-center hover:scale-105 transition-all duration-300">
                            <div
                                class="bg-primary-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                                @if($category->category_img)
                                    <img src="{{ asset('imgs/categories/' . $category->category_img) }}"
                                        alt="{{ $category->category_name }}" class="w-12 h-12 object-contain">
                                @else
                                    <i class="fas fa-desktop text-3xl text-primary"></i>
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-800 group-hover:text-primary transition">
                                {{ $category->category_name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Flash Sale / Hot Deals -->
    <section class="py-12 bg-gradient-to-r from-red-500 to-orange-500">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-bolt text-yellow-300 text-4xl animate-pulse"></i>
                    <div>
                        <h2 class="text-3xl font-bold text-white">Flash Sale</h2>
                        <p class="text-white/90">Khuyến mãi có thời hạn - Nhanh tay kẻo lỡ!</p>
                    </div>
                </div>
                <div
                    class="hidden md:flex items-center space-x-2 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2 text-white">
                    <i class="fas fa-clock"></i>
                    <div class="flex space-x-2 font-bold text-xl">
                        <span>02</span><span>:</span><span>45</span><span>:</span><span>30</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="product-card group"
                        onclick="window.location='{{ route('products.show', $product->product_id) }}'">
                        <div class="relative">
                            {{-- Hiển thị ảnh thật từ thư mục public/imgs --}}
                            @if($product->product_avt_img)
                                <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-48 object-cover"
                                    onerror="this.src='{{ asset('imgs/default.png') }}'">
                            @elseif($product->images->isNotEmpty())
                                {{-- Fallback về ảnh đầu tiên trong gallery nếu không có ảnh chính --}}
                                <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->images->first()->image_name) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-48 object-cover"
                                    onerror="this.src='{{ asset('imgs/default.png') }}'">
                            @else
                                {{-- Ảnh mặc định --}}
                                <img src="{{ asset('imgs/default.png') }}" alt="Chưa có ảnh"
                                    class="w-full h-48 object-cover bg-gray-200">
                            @endif
                            <button
                                class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-primary hover:text-white">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition">
                                {{ $product->product_name }}
                            </h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->product_rate ?? 0))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= ($product->product_rate ?? 0))
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span
                                    class="text-gray-500 text-sm ml-2">({{ number_format($product->product_rate ?? 0, 1) }})</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->min_price)
                                        <p class="text-red-600 font-bold text-lg">
                                            {{ number_format($product->min_price, 0, ',', '.') }}₫</p>
                                    @else
                                        <p class="text-red-600 font-bold text-lg">Liên hệ</p>
                                    @endif
                                </div>
                                <button class="bg-primary text-white w-10 h-10 rounded-lg hover:bg-primary-600 transition"
                                    onclick="event.stopPropagation();">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}"
                    class="inline-block bg-white text-red-500 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
                    Xem tất cả Flash Sale <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Best Sellers -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold">Sản phẩm bán chạy</h2>
                    <p class="text-gray-600">Top sản phẩm được khách hàng yêu thích nhất</p>
                </div>
                <a href="{{ route('products.index') }}" class="hidden md:inline-block btn-outline">
                    Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($bestsellerProducts as $product)
                    <div class="product-card group"
                        onclick="window.location='{{ route('products.show', $product->product_id) }}'">
                        <div class="relative">
                            <span class="absolute top-2 left-2 badge-new">HOT</span>
                            @if($product->product_avt_img)
                                <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-48 object-cover"
                                    onerror="this.src='{{ asset('imgs/default.png') }}'">
                            @elseif($product->images->isNotEmpty())
                                {{-- Fallback về ảnh đầu tiên trong gallery nếu không có ảnh chính --}}
                                <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->images->first()->image_name) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-48 object-cover"
                                    onerror="this.src='{{ asset('imgs/default.png') }}'">
                            @else
                                {{-- Ảnh mặc định --}}
                                <img src="{{ asset('imgs/default.png') }}" alt="Chưa có ảnh"
                                    class="w-full h-48 object-cover bg-gray-200">
                            @endif
                            <button
                                class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-primary hover:text-white">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition">
                                {{ $product->product_name }}
                            </h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->product_rate ?? 0))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= ($product->product_rate ?? 0))
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span
                                    class="text-gray-500 text-sm ml-2">({{ number_format($product->product_rate ?? 0, 1) }})</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->min_price)
                                        <p class="text-primary font-bold text-lg">
                                            {{ number_format($product->min_price, 0, ',', '.') }}₫</p>
                                    @else
                                        <p class="text-primary font-bold text-lg">Liên hệ</p>
                                    @endif
                                </div>
                                <button class="bg-primary text-white w-10 h-10 rounded-lg hover:bg-primary-600 transition"
                                    onclick="event.stopPropagation();">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- New Products -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold">Sản phẩm mới nhất</h2>
                    <p class="text-gray-600">Cập nhật liên tục các sản phẩm mới nhất</p>
                </div>
                <a href="{{ route('products.index') }}" class="hidden md:inline-block btn-outline">
                    Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($newProducts as $product)
                    <div class="product-card group"
                        onclick="window.location='{{ route('products.show', $product->product_id) }}'">
                        <div class="relative">
                            <span class="absolute top-2 left-2 badge-new">MỚI</span>
                            @if($product->product_avt_img)
                                <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-48 object-cover"
                                    onerror="this.src='{{ asset('imgs/default.png') }}'">
                            @elseif($product->images->isNotEmpty())
                                {{-- Fallback về ảnh đầu tiên trong gallery nếu không có ảnh chính --}}
                                <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->images->first()->image_name) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-48 object-cover"
                                    onerror="this.src='{{ asset('imgs/default.png') }}'">
                            @else
                                {{-- Ảnh mặc định --}}
                                <img src="{{ asset('imgs/default.png') }}" alt="Chưa có ảnh"
                                    class="w-full h-48 object-cover bg-gray-200">
                            @endif
                            <button
                                class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-primary hover:text-white">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition">
                                {{ $product->product_name }}
                            </h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->product_rate ?? 0))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= ($product->product_rate ?? 0))
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span
                                    class="text-gray-500 text-sm ml-2">({{ number_format($product->product_rate ?? 0, 1) }})</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->min_price)
                                        <p class="text-primary font-bold text-lg">
                                            {{ number_format($product->min_price, 0, ',', '.') }}₫</p>
                                    @else
                                        <p class="text-primary font-bold text-lg">Liên hệ</p>
                                    @endif
                                </div>
                                <button class="bg-primary text-white w-10 h-10 rounded-lg hover:bg-primary-600 transition"
                                    onclick="event.stopPropagation();">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Brand Partners -->
    <section class="py-12 bg-white border-t">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">Thương hiệu đối tác</h2>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-8 items-center">
                @foreach($suppliers as $supplier)
                    <div class="grayscale hover:grayscale-0 transition cursor-pointer">
                        @if($supplier->supplier_logo)
                            <img src="{{ asset('imgs/logo-brand/' . $supplier->supplier_logo) }}"
                                alt="{{ $supplier->supplier_name }}" class="w-full h-20 object-contain">
                        @else
                            <div class="w-full h-20 bg-gray-100 rounded flex items-center justify-center">
                                <span class="text-gray-500 text-sm text-center">{{ $supplier->supplier_name }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-12 bg-gradient-to-r from-primary to-primary-700 text-white">
        <div class="container mx-auto px-4 text-center">
            <i class="fas fa-envelope-open-text text-5xl mb-4"></i>
            <h2 class="text-3xl font-bold mb-4">Đăng ký nhận tin khuyến mãi</h2>
            <p class="text-lg mb-8">Nhận ngay voucher 100K cho đơn hàng đầu tiên!</p>
            <form class="max-w-md mx-auto flex gap-4">
                <input type="email" placeholder="Nhập email của bạn"
                    class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                <button type="submit"
                    class="bg-white text-primary px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
                    Đăng ký
                </button>
            </form>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // Add to cart functionality (example)
        document.querySelectorAll('.product-card button:has(.fa-shopping-cart)').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                // Add animation
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-shopping-cart"></i>';
                }, 1000);
            });
        });
    </script>
@endpush