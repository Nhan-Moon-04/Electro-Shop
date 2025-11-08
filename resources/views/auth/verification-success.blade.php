<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Xác nhận thành công</title>
</head>

<body>
    <h1>Tài khoản đã được kích hoạt</h1>
    <p>Bạn có thể đăng nhập ngay bây giờ.</p>

</body>
<script>
    messageDiv.innerText = data.message + ' Đang chuyển bạn về trang đăng nhập...';
    messageDiv.className = 'mb-4 p-4 rounded-lg text-green-700 bg-green-100';
    messageDiv.style.display = 'block';
    setTimeout(() => {
        window.location.href = '/login'; // Chuyển về trang login
    }, 3000);
</script>

</html>