@extends('layouts.app')

@section('title', 'Danh sách sản phẩm - ElectroShop')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                <li><span class="text-gray-800 font-medium">Sản phẩm</span></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-1">
            <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    
                    <!-- Category Filter -->
                    <div class="mb-6">
                        <h3 class="font-bold text-lg mb-4 flex items-center justify-between">
                            <span><i class="fas fa-th-large text-primary mr-2"></i>Danh mục</span>
                        </h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                            <li>
                                <label class="flex items-center cursor-pointer hover:text-primary transition">
                                    <input type="checkbox" name="category[]" value="{{ $category->category_id }}" 
                                           class="mr-2 rounded text-primary" 
                                           {{ in_array($category->category_id, request('category', [])) ? 'checked' : '' }}>
                                    <span>{{ $category->category_name }}</span>
                                    <span class="ml-auto text-gray-400 text-sm">({{ $category->products_count }})</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <hr class="my-6">

                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h3 class="font-bold text-lg mb-4 flex items-center">
                            <i class="fas fa-tags text-primary mr-2"></i>Khoảng giá
                        </h3>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="radio" name="price_range" value="under_2m" class="mr-2"
                                       {{ request('price_range') == 'under_2m' ? 'checked' : '' }}>
                                <span>Dưới 2 triệu</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="radio" name="price_range" value="2m_5m" class="mr-2"
                                       {{ request('price_range') == '2m_5m' ? 'checked' : '' }}>
                                <span>2 - 5 triệu</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="radio" name="price_range" value="5m_10m" class="mr-2"
                                       {{ request('price_range') == '5m_10m' ? 'checked' : '' }}>
                                <span>5 - 10 triệu</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="radio" name="price_range" value="10m_20m" class="mr-2"
                                       {{ request('price_range') == '10m_20m' ? 'checked' : '' }}>
                                <span>10 - 20 triệu</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="radio" name="price_range" value="over_20m" class="mr-2"
                                       {{ request('price_range') == 'over_20m' ? 'checked' : '' }}>
                                <span>Trên 20 triệu</span>
                            </label>
                        </div>
                    </div>

                    <hr class="my-6">

                    <!-- Brand Filter -->
                    <div class="mb-6">
                        <h3 class="font-bold text-lg mb-4 flex items-center">
                            <i class="fas fa-copyright text-primary mr-2"></i>Thương hiệu
                        </h3>
                        <div class="space-y-2">
                            @foreach($suppliers as $supplier)
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="checkbox" name="supplier[]" value="{{ $supplier->supplier_id }}" 
                                       class="mr-2 rounded text-primary"
                                       {{ in_array($supplier->supplier_id, request('supplier', [])) ? 'checked' : '' }}>
                                <span>{{ $supplier->supplier_name }}</span>
                                <span class="ml-auto text-gray-400 text-sm">({{ $supplier->products_count }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-6">

                    <!-- Rating Filter -->
                    <div class="mb-6">
                        <h3 class="font-bold text-lg mb-4 flex items-center">
                            <i class="fas fa-star text-primary mr-2"></i>Đánh giá
                        </h3>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="radio" name="rating" value="5" class="mr-2 rounded text-primary"
                                       {{ request('rating') == '5' ? 'checked' : '' }}>
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <span class="ml-2">Từ 5 sao</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="radio" name="rating" value="4" class="mr-2 rounded text-primary"
                                       {{ request('rating') == '4' ? 'checked' : '' }}>
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= 4)
                                        <i class="fas fa-star"></i>
                                        @else
                                        <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2">Từ 4 sao</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:text-primary transition">
                                <input type="radio" name="rating" value="3" class="mr-2 rounded text-primary"
                                       {{ request('rating') == '3' ? 'checked' : '' }}>
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= 3)
                                        <i class="fas fa-star"></i>
                                        @else
                                        <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2">Từ 3 sao</span>
                            </label>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <a href="{{ route('products.index') }}" class="w-full btn-secondary text-sm block text-center">
                        <i class="fas fa-redo mr-2"></i>Xóa bộ lọc
                    </a>
                </div>
            </form>
        </aside>

        <!-- Products Grid -->
        <main class="lg:col-span-3">
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 bg-white rounded-lg shadow-md p-4">
                <div>
                    <h1 class="text-2xl font-bold mb-1">Tất cả sản phẩm</h1>
                    <p class="text-gray-600">
                        Hiển thị {{ $products->firstItem() }}-{{ $products->lastItem() }} 
                        trong tổng số {{ $products->total() }} sản phẩm
                    </p>
                </div>
                
                <!-- Sort Options -->
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <span class="text-gray-600">Sắp xếp:</span>
                    <select name="sort" onchange="document.getElementById('filterForm').submit()" 
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Mặc định</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến thấp</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="bestseller" {{ request('sort') == 'bestseller' ? 'selected' : '' }}>Bán chạy nhất</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @forelse($products as $product)
                <div class="product-card group" onclick="window.location='{{ route('products.show', $product->product_id) }}'">
                    <div class="relative">
                        @php
                            $minVariant = $product->variants->first();
                            $hasDiscount = $minVariant && $minVariant->discount_id;
                        @endphp
                        
                        @if($hasDiscount)
                        <span class="absolute top-2 left-2 badge-sale">SALE</span>
                        @endif
                        
                        @if($product->variants->where('product_variant_is_bestseller', true)->count() > 0)
                        <span class="absolute top-2 right-2 badge-new">HOT</span>
                        @endif
                        
                        {{-- Hiển thị ảnh từ thư mục public/imgs --}}
    @if($product->product_avt_img)
    <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->product_avt_img) }}" 
         alt="{{ $product->product_name }}" 
         class="w-full h-48 object-cover"
         onerror="this.src='{{ asset('imgs/default.png') }}'">
@elseif($product->images->isNotEmpty())
    {{-- Fallback về ảnh đầu tiên trong gallery nếu không có ảnh chính --}}
    <img src="{{ asset('imgs/product_image/P' . $product->product_id . '/' . $product->images->first()->image_name) }}" 
         alt="{{ $product->product_name }}" 
         class="w-full h-48 object-cover"
         onerror="this.src='{{ asset('imgs/default.png') }}'">
@else
    {{-- Ảnh mặc định --}}
    <img src="{{ asset('imgs/default.png') }}" 
         alt="Chưa có ảnh" 
         class="w-full h-48 object-cover bg-gray-200">
@endif
                        
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent p-4 opacity-0 group-hover:opacity-100 transition">
                            <button class="w-full bg-white text-primary py-2 rounded-lg font-medium hover:bg-primary hover:text-white transition"
                                    onclick="event.stopPropagation();">
                                Xem chi tiết
                            </button>
                        </div>
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
                            <span class="text-gray-500 text-sm ml-2">({{ $product->product_view_count }})</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                @if($product->min_price)
                                    <p class="text-primary font-bold text-lg">{{ number_format($product->min_price, 0, ',', '.') }}₫</p>
                                @else
                                    <p class="text-primary font-bold text-lg">Liên hệ</p>
                                @endif
                            </div>
                            <button class="bg-primary text-white w-10 h-10 rounded-lg hover:bg-primary-600 transition" 
                                    onclick="event.stopPropagation(); addToCart({{ $product->product_id }})">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Không tìm thấy sản phẩm</h3>
                    <p class="text-gray-500">Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            {{ $products->links() }}
        </main>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto submit form when filter changes
    document.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    function addToCart(productId) {
        const token = localStorage.getItem('auth_token');
        
        if (!token) {
            alert('Vui lòng đăng nhập để thêm vào giỏ hàng!');
            window.location.href = '/login';
            return;
        }

        // Get first variant ID from product
        fetch(`/api/products/${productId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Không thể tải thông tin sản phẩm');
            }
            return response.json();
        })
        .then(productData => {
            if (!productData.variants || productData.variants.length === 0) {
                alert('Sản phẩm tạm hết hàng!');
                return;
            }

            const firstVariant = productData.variants[0];
            
            // Add to cart
            fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_variant_id: firstVariant.product_variant_id,
                    quantity: 1
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
                    // Show notification
                    alert('✓ Đã thêm sản phẩm vào giỏ hàng!');
                    
                    // Update cart count
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }
                } else {
                    alert(data.message || data.error || 'Có lỗi xảy ra khi thêm vào giỏ hàng!');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                alert('Không thể thêm vào giỏ hàng. Vui lòng thử lại!');
            });
        })
        .catch(error => {
            console.error('Error fetching product:', error);
            alert('Không thể tải thông tin sản phẩm!');
        });
    }
</script>
@endpush
