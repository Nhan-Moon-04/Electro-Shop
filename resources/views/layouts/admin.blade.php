<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - ElectroShop Admin</title>
    
    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
    
    <div class="flex h-screen overflow-hidden">
        
        {{-- Sidebar --}}
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-primary to-primary-700 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            
            {{-- Logo --}}
            <div class="flex items-center justify-between h-16 px-6 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <i class="fas fa-bolt text-2xl text-yellow-400"></i>
                    <span class="text-xl font-bold">ElectroShop</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            {{-- User Info --}}
            <div class="px-6 py-4 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <p class="font-semibold">{{ $currentAdmin->admin_name ?? 'Admin' }}</p>
                        <p class="text-xs text-white/70">{{ $currentAdmin->admin_role ?? 'administrator' }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Navigation --}}
            <nav class="px-4 py-6 overflow-y-auto h-[calc(100vh-180px)]">
                <div class="space-y-2">
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    {{-- Products --}}
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.products.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-box w-5"></i>
                        <span>Sản phẩm</span>
                    </a>
                    
                    {{-- Categories --}}
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.categories.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-tags w-5"></i>
                        <span>Danh mục</span>
                    </a>
                    
                    {{-- Orders --}}
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.orders.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span>Đơn hàng</span>
                       
                    </a>
                    
                    {{-- Customers --}}
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.customers.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Khách hàng</span>
                    </a>
                    
                    {{-- Suppliers --}}
                    <a href="{{ route('admin.suppliers.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-truck w-5"></i>
                        <span>Nhà cung cấp</span> 
                    </a>
                    
                    {{-- Discounts --}}
                   <a href="{{ route('admin.discounts.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.discounts.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-percent w-5"></i>
                        <span>Khuyến mãi</span>
                    </a>
                    
                    <hr class="my-4 border-white/10">
                    
                    {{-- Backup & Restore --}}
                    <a href="{{ route('admin.backup.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.backup.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-database w-5"></i>
                        <span>Backup & Restore</span>
                    </a>
                    
                    {{-- Settings --}}
                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                        <i class="fas fa-cog w-5"></i>
                        <span>Cài đặt</span>
                    </a>
                    
                    {{-- Back to Website --}}
                    <a href="{{ route('home') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                        <i class="fas fa-globe w-5"></i>
                        <span>Xem website</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            
            {{-- Header --}}
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-6">
                    {{-- Mobile Menu Button --}}
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    {{-- Search Bar --}}
                    <div class="flex-1 max-w-2xl mx-4">
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Tìm kiếm sản phẩm, đơn hàng, khách hàng..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    {{-- Right Actions --}}
                    <div class="flex items-center space-x-4">
                        {{-- Notifications --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="relative text-gray-600 hover:text-gray-900">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">Thông báo</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition">
                                        <p class="text-sm font-medium text-gray-800">Đơn hàng mới #1234</p>
                                        <p class="text-xs text-gray-500 mt-1">5 phút trước</p>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition">
                                        <p class="text-sm font-medium text-gray-800">Sản phẩm sắp hết hàng</p>
                                        <p class="text-xs text-gray-500 mt-1">1 giờ trước</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        {{-- User Menu --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-user"></i>
                                </div>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <i class="fas fa-user-circle mr-2"></i>Tài khoản
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <i class="fas fa-cog mr-2"></i>Cài đặt
                                </a>
                                <hr class="my-2">
                                <a href="{{ route('home') }}" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 transition block">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Về trang chủ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-cloak
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>
    
    {{-- Admin Token Handler --}}
    <script>
        // Get token from localStorage
        const adminToken = localStorage.getItem('admin_token');
        
        // If token exists, set it to all fetch requests
        if (adminToken) {
            const originalFetch = window.fetch;
            window.fetch = function(url, options = {}) {
                options.headers = options.headers || {};
                options.headers['X-Admin-Token'] = adminToken;
                options.headers['Authorization'] = 'Bearer ' + adminToken;
                return originalFetch(url, options);
            };
        } else {
            // No token found, redirect to login
            console.warn('No admin token found');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
