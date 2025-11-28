<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - ElectroShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            width: 100%;
        }
        .btn-login:hover {
            opacity: 0.9;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>⚡ Admin Login</h2>
            <p>Đăng nhập vào hệ thống quản trị</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
        <div id="success-message" class="alert alert-success" style="display: none;"></div>

        <form id="adminLoginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-login">
                <span id="btnText">Đăng nhập</span>
                <span id="btnLoading" style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Đang xử lý...
                </span>
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="/" class="text-muted">← Quay lại trang chủ</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('adminLoginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');

            // Hide messages
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';

            // Show loading
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';

            const formData = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                console.log('Login response:', data);

                if (response.ok) {
                    if (data.user_type === 'admin') {
                        successMessage.textContent = 'Đăng nhập thành công! Đang chuyển hướng...';
                        successMessage.style.display = 'block';
                        
                        // Save token to localStorage
                        localStorage.setItem('admin_token', data.access_token);
                        console.log('Token saved to localStorage');
                        
                        // Set token to cookie via backend route
                        try {
                            const setCookieResponse = await fetch('/admin/set-token', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                                },
                                body: JSON.stringify({ token: data.access_token })
                            });
                            
                            const cookieResult = await setCookieResponse.json();
                            console.log('Cookie set result:', cookieResult);
                            console.log('Cookies after set:', document.cookie);
                            
                            // Now redirect to admin
                            setTimeout(() => {
                                console.log('Redirecting to /admin...');
                                window.location.href = '/admin';
                            }, 300);
                        } catch (error) {
                            console.error('Error setting cookie:', error);
                            // Still try to redirect
                            window.location.href = '/admin';
                        }
                    } else {
                        errorMessage.textContent = 'Tài khoản này không có quyền truy cập admin.';
                        errorMessage.style.display = 'block';
                        btnText.style.display = 'inline';
                        btnLoading.style.display = 'none';
                    }
                } else {
                    errorMessage.textContent = data.error || 'Email hoặc mật khẩu không đúng';
                    errorMessage.style.display = 'block';
                    btnText.style.display = 'inline';
                    btnLoading.style.display = 'none';
                }
            } catch (error) {
                errorMessage.textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
                errorMessage.style.display = 'block';
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
            }
        });
    </script>
</body>
</html>
