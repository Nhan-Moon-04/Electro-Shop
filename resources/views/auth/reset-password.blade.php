<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - ElectroShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

                <h2 class="text-xl font-bold mb-6 text-center">Đặt lại mật khẩu</h2>

                <div id="message" class="mb-4 p-4 rounded-lg" style="display: none;"></div>

                <form id="resetPasswordForm">
                    <input type="hidden" id="resetToken" value="{{ $token }}">

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="resetEmail" class="input-field" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới</label>
                        <input type="password" id="resetPassword" class="input-field" required>
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Xác nhận
                            mật khẩu mới</label>
                        <input type="password" id="resetPasswordConfirmation" class="input-field" required>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn-primary w-full">Lưu mật khẩu</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'none';

            const formData = {
                token: document.getElementById('resetToken').value,
                email: document.getElementById('resetEmail').value,
                password: document.getElementById('resetPassword').value,
                password_confirmation: document.getElementById('resetPasswordConfirmation').value
            };

            fetch('/api/auth/reset-password', { // Gọi API
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        messageDiv.innerText = data.message + ' Đang chuyển bạn về trang đăng nhập...';
                        messageDiv.className = 'mb-4 p-4 rounded-lg text-green-700 bg-green-100';
                        messageDiv.style.display = 'block';
                        setTimeout(() => {
                            window.location.href = '/login'; // Chuyển về trang login
                        }, 3000);
                    } else {
                        messageDiv.innerText = data.error || 'Có lỗi xảy ra. Token có thể đã hết hạn.';
                        messageDiv.className = 'mb-4 p-4 rounded-lg text-red-700 bg-red-100';
                        messageDiv.style.display = 'block';
                    }
                });
        });
    </script>
</body>

</html>