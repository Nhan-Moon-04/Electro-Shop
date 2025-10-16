@extends('layouts.app')

@section('title', 'Tài khoản của tôi - ElectroShop')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition"><i class="fas fa-home"></i></a></li>
                <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                <li><span class="text-gray-800 font-medium">Tài khoản</span></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- User Info -->
                <div class="text-center mb-6 pb-6 border-b">
                    <div class="w-24 h-24 rounded-full bg-primary/10 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-primary text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">{{ Auth::user()->name ?? 'Nguyễn Văn A' }}</h3>
                    <p class="text-gray-600 text-sm">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('account.profile') }}" class="flex items-center space-x-3 px-4 py-3 bg-primary text-white rounded-lg">
                        <i class="fas fa-user"></i>
                        <span class="font-medium">Thông tin tài khoản</span>
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-box"></i>
                        <span class="font-medium">Đơn hàng của tôi</span>
                    </a>
                    <a href="{{ route('account.addresses') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="font-medium">Sổ địa chỉ</span>
                    </a>
                    <a href="{{ route('account.wishlist') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-heart"></i>
                        <span class="font-medium">Sản phẩm yêu thích</span>
                    </a>
                    <a href="{{ route('account.reviews') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-star"></i>
                        <span class="font-medium">Đánh giá của tôi</span>
                    </a>
                    <a href="{{ route('account.vouchers') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-ticket-alt"></i>
                        <span class="font-medium">Voucher của tôi</span>
                    </a>
                    <a href="{{ route('account.password') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-lock"></i>
                        <span class="font-medium">Đổi mật khẩu</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-red-50 text-red-600 rounded-lg transition">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="font-medium">Đăng xuất</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="lg:col-span-3">
            
            <!-- Profile Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold mb-6">Thông tin tài khoản</h2>
                
                <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? 'Nguyễn Văn A') }}" class="input-field">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? 'email@example.com') }}" class="input-field">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                            <input type="tel" name="phone" value="{{ old('phone', '0912345678') }}" class="input-field">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                            <input type="date" name="birthday" value="{{ old('birthday', '1990-01-01') }}" class="input-field">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
                            <select name="gender" class="input-field">
                                <option value="male">Nam</option>
                                <option value="female">Nữ</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tỉnh/Thành phố</label>
                            <select name="province" class="input-field">
                                <option value="hcm" selected>TP. Hồ Chí Minh</option>
                                <option value="hanoi">Hà Nội</option>
                                <option value="danang">Đà Nẵng</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                            <input type="text" name="address" value="{{ old('address', '123 Đường ABC, Quận 1') }}" class="input-field">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4 mt-6">
                        <button type="button" class="btn-secondary">Hủy</button>
                        <button type="submit" class="btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>

            <!-- Order History -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Đơn hàng gần đây</h2>
                    <a href="{{ route('account.orders') }}" class="text-primary hover:underline">Xem tất cả</a>
                </div>

                <!-- Order Tabs -->
                <div class="flex space-x-4 mb-6 border-b overflow-x-auto">
                    <button class="pb-3 px-4 border-b-2 border-primary text-primary font-medium whitespace-nowrap">
                        Tất cả (5)
                    </button>
                    <button class="pb-3 px-4 border-b-2 border-transparent hover:text-primary transition whitespace-nowrap">
                        Chờ xác nhận (1)
                    </button>
                    <button class="pb-3 px-4 border-b-2 border-transparent hover:text-primary transition whitespace-nowrap">
                        Đang giao (2)
                    </button>
                    <button class="pb-3 px-4 border-b-2 border-transparent hover:text-primary transition whitespace-nowrap">
                        Hoàn thành (2)
                    </button>
                    <button class="pb-3 px-4 border-b-2 border-transparent hover:text-primary transition whitespace-nowrap">
                        Đã hủy (0)
                    </button>
                </div>

                <!-- Order List -->
                <div class="space-y-4">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="font-semibold text-lg">Đơn hàng #DH0000{{ $i }}</p>
                                <p class="text-sm text-gray-600">Ngày đặt: {{ date('d/m/Y', strtotime('-' . $i . ' days')) }}</p>
                            </div>
                            <span class="px-3 py-1 bg-{{ $i == 1 ? 'yellow' : ($i == 2 ? 'blue' : 'green') }}-100 text-{{ $i == 1 ? 'yellow' : ($i == 2 ? 'blue' : 'green') }}-800 text-sm font-medium rounded-full">
                                @if($i == 1) Chờ xác nhận
                                @elseif($i == 2) Đang giao
                                @else Hoàn thành
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center space-x-4 mb-4">
                            <img src="https://via.placeholder.com/80x80/FFFFFF/0066CC?text=Order+{{ $i }}" alt="Product" class="w-20 h-20 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-semibold mb-1">iPhone 15 Pro Max 256GB</h4>
                                <p class="text-sm text-gray-600">x1</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg text-primary">{{ number_format(rand(20,90) * 1000000, 0, ',', '.') }}₫</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t">
                            <p class="text-gray-600">
                                <span class="font-medium">Tổng: </span>
                                <span class="text-lg font-bold text-red-600">{{ number_format(rand(20,90) * 1000000, 0, ',', '.') }}₫</span>
                            </p>
                            <div class="flex space-x-2">
                                <a href="#" class="btn-outline py-2 px-4 text-sm">Xem chi tiết</a>
                                @if($i == 1)
                                <button class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg text-sm transition">Hủy đơn</button>
                                @elseif($i == 2)
                                <button class="btn-primary py-2 px-4 text-sm">Xác nhận đã nhận</button>
                                @else
                                <button class="btn-primary py-2 px-4 text-sm">Mua lại</button>
                                <button class="btn-outline py-2 px-4 text-sm">Đánh giá</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </main>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('button[class*="border-b-2"]').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('button[class*="border-b-2"]').forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent');
            });
            
            // Add active class to clicked tab
            this.classList.add('border-primary', 'text-primary');
            this.classList.remove('border-transparent');
        });
    });
</script>
@endpush
