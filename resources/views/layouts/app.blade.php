<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ElectroShop - Siêu thị điện máy hàng đầu Việt Nam')</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans antialiased">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <!-- Top Bar -->
        <div class="bg-primary text-white">
            <div class="container mx-auto px-4 py-2">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-4">
                        <a href="tel:19001234" class="hover:text-primary-200 transition">
                            <i class="fas fa-phone mr-2"></i>Hotline: 1900 1234
                        </a>
                        <a href="mailto:support@electroshop.vn" class="hover:text-primary-200 transition">
                            <i class="fas fa-envelope mr-2"></i>support@electroshop.vn
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="hover:text-primary-200 transition">Theo dõi đơn hàng</a>
                        <a href="#" class="hover:text-primary-200 transition">Hỗ trợ</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <i class="fas fa-bolt text-primary text-3xl"></i>
                    <span class="text-2xl font-bold text-primary">ElectroShop</span>
                </a>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <form action="/products/search" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Tìm kiếm sản phẩm: tivi, điện thoại, laptop..."
                                class="w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary transition">
                            <button type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-600 transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- User Actions -->
                <div class="flex items-center space-x-6">
                    <!-- Cart -->
                    <a href="/cart" class="relative hover:text-primary transition">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                            3
                        </span>
                    </a>

                    <!-- User Account -->
                    <div id="guest-menu" x-data="{ open: false }" class="relative" style="display: none;">
                        <button @click="open = !open" class="flex items-center space-x-2 hover:text-primary transition">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span class="hidden lg:block">Tài khoản</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                            <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                <i class="fas fa-user-plus mr-2"></i>Đăng ký
                            </a>
                        </div>
                    </div>

                    <div id="user-menu" x-data="{ open: false }" class="relative" style="display: none;">
                        <button @click="open = !open" class="flex items-center space-x-2 hover:text-primary transition">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span class="hidden lg:block" id="nav-user-name">Đang tải...</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                            <a href="/account" class="block px-4 py-2 hover:bg-gray-100 transition">
                                <i class="fas fa-user mr-2"></i>Tài khoản của tôi
                            </a>
                            <a href="#" id="nav-logout-button"
                                class="block px-4 py-2 hover:bg-gray-100 transition text-red-600">
                                <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-2xl">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="md:hidden mt-4">
                <form action="/products/search" method="GET">
                    <div class="relative">
                        <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..."
                            class="w-full px-4 py-2 pr-12 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="bg-gray-100 border-t border-gray-200">
            <div class="container mx-auto px-4">
                <ul class="hidden md:flex items-center justify-center space-x-8 py-3">
                    <li>
                        <a href="/" class="flex items-center space-x-2 hover:text-primary transition font-medium">
                            <i class="fas fa-home"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                    <li x-data="{ open: false }" class="relative">
                        <button @mouseenter="open = true" @mouseleave="open = false"
                            class="flex items-center space-x-2 hover:text-primary transition font-medium">
                            <i class="fas fa-th-large"></i>
                            <span>Danh mục sản phẩm</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <!-- Mega Menu -->
                        <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" x-transition
                            class="absolute left-0 mt-2 w-screen max-w-6xl bg-white rounded-lg shadow-xl p-6 z-50 -ml-32">
                            <div class="grid grid-cols-4 gap-6">
                                <div>
                                    <h3 class="font-bold text-primary mb-3">Điện thoại & Tablet</h3>
                                    <ul class="space-y-2 text-sm">
                                        <li><a href="#" class="hover:text-primary transition">Điện thoại</a></li>
                                        <li><a href="#" class="hover:text-primary transition">Tablet</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-bold text-primary mb-3">Laptop & PC</h3>
                                    <ul class="space-y-2 text-sm">
                                        <li><a href="#" class="hover:text-primary transition">Laptop</a></li>
                                        <li><a href="#" class="hover:text-primary transition">Màn hình</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-bold text-primary mb-3">Thiết bị âm thanh</h3>
                                    <ul class="space-y-2 text-sm">
                                        <li><a href="#" class="hover:text-primary transition">Tivi</a></li>
                                        <li><a href="#" class="hover:text-primary transition">Loa</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-bold text-primary mb-3">Đồ gia dụng</h3>
                                    <ul class="space-y-2 text-sm">
                                        <li><a href="#" class="hover:text-primary transition">Máy giặt</a></li>
                                        <li><a href="#" class="hover:text-primary transition">Tủ lạnh</a></li>
                                        <li><a href="#" class="hover:text-primary transition">Máy lạnh</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="/products" class="hover:text-primary transition font-medium">Sản phẩm</a></li>
                    <li><a href="#" class="hover:text-primary transition font-medium">Khuyến mãi</a></li>
                    <li><a href="#" class="hover:text-primary transition font-medium">Tin tức</a></li>
                    <li><a href="#" class="hover:text-primary transition font-medium">Liên hệ</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 pt-12 pb-6 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-bolt text-primary text-2xl"></i>
                        <span class="text-xl font-bold text-white">ElectroShop</span>
                    </div>
                    <p class="text-sm mb-4">Siêu thị điện máy uy tín hàng đầu Việt Nam với hơn 10 năm kinh nghiệm</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-2xl hover:text-primary transition"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl hover:text-primary transition"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-2xl hover:text-primary transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-2xl hover:text-primary transition"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Customer Support -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">Hỗ trợ khách hàng</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-primary transition">Hướng dẫn mua hàng</a></li>
                        <li><a href="#" class="hover:text-primary transition">Chính sách thanh toán</a></li>
                        <li><a href="#" class="hover:text-primary transition">Chính sách vận chuyển</a></li>
                        <li><a href="#" class="hover:text-primary transition">Chính sách đổi trả</a></li>
                        <li><a href="#" class="hover:text-primary transition">Chính sách bảo hành</a></li>
                    </ul>
                </div>

                <!-- About Us -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">Về chúng tôi</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-primary transition">Giới thiệu công ty</a></li>
                        <li><a href="#" class="hover:text-primary transition">Hệ thống cửa hàng</a></li>
                        <li><a href="#" class="hover:text-primary transition">Tuyển dụng</a></li>
                        <li><a href="#" class="hover:text-primary transition">Tin tức</a></li>
                        <li><a href="#" class="hover:text-primary transition">Liên hệ</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">Liên hệ</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-1 mr-3"></i>
                            <span>123 Đường ABC, Quận 1, TP.HCM</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-primary mr-3"></i>
                            <span>Hotline: 1900 1234</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-primary mr-3"></i>
                            <span>support@electroshop.vn</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock text-primary mr-3"></i>
                            <span>8:00 - 22:00 (Hàng ngày)</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="border-t border-gray-700 pt-6 mb-6">
                <h4 class="text-white font-semibold mb-4">Phương thức thanh toán</h4>
                <div class="flex flex-wrap gap-4">
                    <div class="bg-white rounded p-2">
                        <i class="fab fa-cc-visa text-3xl text-blue-600"></i>
                    </div>
                    <div class="bg-white rounded p-2">
                        <i class="fab fa-cc-mastercard text-3xl text-red-600"></i>
                    </div>
                    <div class="bg-white rounded p-2">
                        <i class="fas fa-credit-card text-3xl text-green-600"></i>
                    </div>
                    <div class="bg-white rounded p-2 px-3">
                        <span class="font-bold text-sm">MOMO</span>
                    </div>
                    <div class="bg-white rounded p-2 px-3">
                        <span class="font-bold text-sm">VNPay</span>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-700 pt-6 text-center text-sm">
                <p>&copy; 2025 ElectroShop. All rights reserved. Designed with <i class="fas fa-heart text-red-500"></i>
                    by ElectroShop Team</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop"
        class="fixed bottom-8 right-8 bg-primary text-white w-12 h-12 rounded-full shadow-lg hover:bg-primary-600 transition-all duration-300 opacity-0 pointer-events-none z-50"
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    @stack('scripts')

    <script>
        // Back to top button
        window.addEventListener('scroll', function () {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.classList.remove('opacity-0', 'pointer-events-none');
                backToTop.classList.add('opacity-100', 'pointer-events-auto');
            } else {
                backToTop.classList.add('opacity-0', 'pointer-events-none');
                backToTop.classList.remove('opacity-100', 'pointer-events-auto');
            }
        });
    </script>

    <script>
        // Hàm gọi ở trang account/profile.blade.php
        function handleLogout() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });
            }
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('auth_token');
            const guestMenu = document.getElementById('guest-menu');
            const userMenu = document.getElementById('user-menu');

            if (token) {
                fetch('/api/auth/me', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id) {
                            userMenu.style.display = 'block';
                            document.getElementById('nav-user-name').innerText = data.name;
                        } else {
                            handleLogout();
                        }
                    })
                    .catch(error => {
                        handleLogout();
                    });
            } else {
                guestMenu.style.display = 'block';
            }

            const logoutButton = document.getElementById('nav-logout-button');
            if (logoutButton) {
                logoutButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    handleLogout();
                });
            }
        });
    </script>


</body>

</html>