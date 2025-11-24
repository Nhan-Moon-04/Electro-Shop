<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Đăng ký - ElectroShop</title>

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

                <h2 class="text-2xl font-bold mb-6 text-center">Tạo tài khoản</h2>

                <div id="registerMessage" class="mb-4 p-4 rounded-lg" style="display: none;"></div>

                <form id="registerForm">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                        <input type="text" id="registerName" class="input-field" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="registerEmail" class="input-field" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu</label>
                        <input type="password" id="registerPassword" class="input-field" required>
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Xác nhận
                            mật khẩu</label>
                        <input type="password" id="registerPasswordConfirmation" class="input-field" required>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn-primary w-full">Đăng ký</button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-primary hover:underline">Đã có tài khoản? Đăng
                            nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const messageDiv = document.getElementById('registerMessage');
            messageDiv.style.display = 'none';

            fetch('/api/auth/register', { // Gọi API register
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('registerName').value,
                    email: document.getElementById('registerEmail').value,
                    password: document.getElementById('registerPassword').value,
                    password_confirmation: document.getElementById('registerPasswordConfirmation').value
                })
            })
                .then(response => {
                    return response.json().then(data => {
                        return { data: data, status: response.status };
                    });
                })
                .then(result => {
                    const data = result.data;
                    const status = result.status;

                    if (status === 201 && data.message) { // Đăng ký thành công
                        messageDiv.innerText = data.message + ' Đang chuyển bạn đến trang đăng nhập...';
                        messageDiv.className = 'mb-4 p-4 rounded-lg text-green-700 bg-green-100';
                        messageDiv.style.display = 'block';
                        setTimeout(() => {
                            window.location.href = '/login'; // Chuyển sang trang login
                        }, 3000);
                    } else if (status === 400 || data.errors) { // Có lỗi validation
                        let errorMsg = '';
                        if (data.errors) {
                            errorMsg = '<ul>';
                            for (let key in data.errors) {
                                data.errors[key].forEach(error => {
                                    errorMsg += '<li>' + error + '</li>';
                                });
                            }
                            errorMsg += '</ul>';
                        } else if (data.error) {
                            errorMsg = data.error;
                        } else {
                            errorMsg = 'Có lỗi xảy ra, vui lòng thử lại.';
                        }
                        messageDiv.innerHTML = errorMsg;
                        messageDiv.className = 'mb-4 p-4 rounded-lg text-red-700 bg-red-100';
                        messageDiv.style.display = 'block';
                    } else if (data.error) { // Lỗi khác
                        messageDiv.innerText = data.error;
                        messageDiv.className = 'mb-4 p-4 rounded-lg text-red-700 bg-red-100';
                        messageDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    messageDiv.innerText = 'Lỗi kết nối. Vui lòng thử lại.';
                    messageDiv.className = 'mb-4 p-4 rounded-lg text-red-700 bg-red-100';
                    messageDiv.style.display = 'block';
                });
        });
    </script>
</body>

</html>