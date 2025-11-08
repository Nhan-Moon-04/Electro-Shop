<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Xác nhận thất bại</title>
</head>

<body>
    <h1>Liên kết xác nhận không hợp lệ hoặc đã hết hạn</h1>
    <p>Vui lòng thử đăng ký lại hoặc liên hệ quản trị viên.</p>
</body>
<script>

    messageDiv.innerText = data.message; + ' Đang chuyển bạn về trang đăng nhập...';
    messageDiv.className = 'mb-4 p-4 rounded-lg text-green-700 bg-green-100';
    messageDiv.style.display = 'block';
    setTimeout(() => {
        window.location.href = '/login'; // Chuyển về trang login
    }, 3000);

</script>

</html>