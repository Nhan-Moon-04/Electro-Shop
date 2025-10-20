<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanh toán - Đơn hàng #{{ $order->order_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-full max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Thanh toán cho đơn hàng #{{ $order->order_id }}</h1>

        <div class="mb-4">
            <p><strong>Người nhận:</strong> {{ $order->order_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->order_phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->order_delivery_address }}</p>
            <p><strong>Tổng:</strong> {{ number_format($order->order_total_after ?? 0) }} VNĐ</p>
            <p class="mt-2"><strong>Trạng thái:</strong> <span id="status">{{ $order->order_status }}</span></p>
        </div>

        <div class="flex gap-2">
            <a href="/" class="bg-gray-500 text-white px-4 py-2 rounded">Quay lại</a>
            <!-- Simulate webhook for testing -->
            <button id="simulate" class="bg-blue-600 text-white px-4 py-2 rounded">Simulate provider webhook (mark
                paid)</button>
        </div>

        <script>
            document.getElementById('simulate').addEventListener('click', async function () {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('/payment/webhook', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ order_id: {{ $order->order_id }}, status: 'paid' })
                });
                const data = await res.json();
                if (data.ok) {
                    document.getElementById('status').innerText = 'Đang giao hàng';
                    alert('Order marked as paid');
                } else {
                    alert('Webhook failed: ' + (data.error || JSON.stringify(data)));
                }
            });
        </script>
    </div>
</body>

</html>