@extends('layouts.app')

@section('title', 'Tài khoản của tôi - ElectroShop')

@section('content')

    <div class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition"><i
                                class="fas fa-home"></i></a></li>
                    <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                    <li><span class="text-gray-800 font-medium">Tài khoản</span></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

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
                            class="flex items-center space-x-3 px-4 py-3 bg-primary text-white rounded-lg">
                            <i class="fas fa-user"></i>
                            <span class="font-medium">Thông tin tài khoản</span>
                        </a>
                        <a href="{{ route('account.orders') }}"
                            class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
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
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-star"></i>
                            <span class="font-medium">Đánh giá của tôi</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-ticket-alt"></i>
                            <span class="font-medium">Voucher của tôi</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-lock"></i>
                            <span class="font-medium">Đổi mật khẩu</span>
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

            <main class="lg:col-span-3">

                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6">Thông tin tài khoản</h2>

                    <form id="account-update-form">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                                <input type="text" name="name" value="" class="input-field" placeholder="Đang tải...">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="" class="input-field" placeholder="Đang tải...">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                                <input type="tel" name="phone" value="" class="input-field" placeholder="Chưa có">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                                <input type="text" name="address" value="" class="input-field" placeholder="Chưa có">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 mt-6">
                            <button type="button" class="btn-secondary">Hủy</button>
                            <button type="submit" class="btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                </div>
            </main>
        </div>
    </div>

@endsection

@push('scripts')
    <script>

        document.querySelectorAll('button[class*="border-b-2"]').forEach(button => {
            button.addEventListener('click', function () {

            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('auth_token');


            const accountNameEl = document.getElementById('account-name');
            const accountEmailEl = document.getElementById('account-email');
            const formInputName = document.querySelector('input[name="name"]');
            const formInputEmail = document.querySelector('input[name="email"]');
            const formInputPhone = document.querySelector('input[name="phone"]');

            if (!token) {

                if (typeof handleLogout === 'function') {
                    handleLogout();
                } else {
                    localStorage.removeItem('auth_token');
                    window.location.href = '/login';
                }
                return;
            }


            fetch('/api/auth/me', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.id) {

                        if (accountNameEl) accountNameEl.innerText = data.name;
                        if (accountEmailEl) accountEmailEl.innerText = data.email;


                        if (formInputName) formInputName.value = data.name;
                        if (formInputEmail) formInputEmail.value = data.email;
                        if (formInputPhone && data.phone && data.phone !== '0000000000') {
                            formInputPhone.value = data.phone;
                        }

                        // Update address if available
                        const formInputAddress = document.querySelector('input[name="address"]');
                        if (formInputAddress && data.address) {
                            formInputAddress.value = data.address;
                        }


                    } else {

                        if (typeof handleLogout === 'function') handleLogout();
                    }
                })
                .catch(error => {

                    if (typeof handleLogout === 'function') handleLogout();
                });


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

            // Handle update profile form
            const updateForm = document.getElementById('account-update-form');
            if (updateForm) {
                updateForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = {
                        name: document.querySelector('input[name="name"]').value,
                        email: document.querySelector('input[name="email"]').value,
                        phone: document.querySelector('input[name="phone"]').value,
                        address: document.querySelector('input[name="address"]').value,
                    };

                    console.log('Sending update:', formData);

                    fetch('/api/auth/update-profile', {
                        method: 'PUT',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
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

                            console.log('Update response:', data);

                            if (data.success || data.message) {
                                alert('✓ Cập nhật thông tin thành công!');

                                // Update displayed data immediately without reload
                                if (data.user) {
                                    if (accountNameEl) accountNameEl.innerText = data.user.name;
                                    if (accountEmailEl) accountEmailEl.innerText = data.user.email;
                                    if (formInputName) formInputName.value = data.user.name;
                                    if (formInputEmail) formInputEmail.value = data.user.email;

                                    // Update phone if available
                                    const formInputPhone = document.querySelector('input[name="phone"]');
                                    if (formInputPhone && data.user.phone) {
                                        formInputPhone.value = data.user.phone;
                                    }

                                    // Update address if available
                                    const formInputAddress = document.querySelector('input[name="address"]');
                                    if (formInputAddress && data.user.address) {
                                        formInputAddress.value = data.user.address;
                                    }
                                }
                            } else {
                                alert(data.error || 'Có lỗi xảy ra khi cập nhật thông tin!');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating profile:', error);
                            alert('Không thể cập nhật thông tin. Vui lòng thử lại!');
                        });
                });
            }
        });
    </script>
@endpush