<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Đăng nhập - ElectroShop</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-gray-50 font-sans antialiased">

    <main>
        <div class="container mx-auto px-4 py-16" style="max-width: 500px; padding-top: 10vh;">
            <div class="bg-white rounded-lg shadow-md p-8">

                <div class="text-center mb-6">
                    <a href="/" class="flex items-center justify-center space-x-2">
                        <i class="fas fa-bolt text-primary text-3xl"></i>
                        <span class="text-2xl font-bold text-primary">ElectroShop</span>
                    </a>
                </div>

                <h2 class="text-2xl font-bold mb-6 text-center">Đăng nhập</h2>

                <div id="loginErrorMessage" class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg" style="display: none;">
                </div>

                <form id="loginForm">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="loginEmail" class="input-field" required>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu</label>
                        <input type="password" id="loginPassword" class="input-field" required>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn-primary w-full">Đăng nhập</button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('register') }}" class="text-primary hover:underline">Chưa có tài khoản? Đăng
                            ký ngay</a>
                        <br>
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:underline">Quên mật
                            khẩu?</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Ngăn form submit

            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const messageDiv = document.getElementById('loginErrorMessage');
            messageDiv.style.display = 'none'; // Ẩn lỗi cũ

            fetch('/api/auth/login', { // Gọi API login
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email, password: password })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.access_token) {
                        // Đăng nhập THÀNH CÔNG
                        localStorage.setItem('auth_token', data.access_token);
                        window.location.href = '/';
                    } else {
                        // Đăng nhập THẤT BẠI
                        messageDiv.innerText = data.error || 'Email hoặc mật khẩu không đúng.';
                        messageDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    messageDiv.innerText = 'Lỗi kết nối. Vui lòng thử lại.';
                    messageDiv.style.display = 'block';
                });
        });
    </script>
</body>

</html>