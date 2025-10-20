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

        <!-- QR Payment Display -->
        <div id="qr-section" class="bg-blue-50 p-4 rounded-lg mb-4">
            <h2 class="font-semibold mb-3">Quét mã QR để thanh toán</h2>
            <div class="text-center">
                <img id="qr-code" src="" alt="QR Code" class="mx-auto mb-3" style="max-width:220px;">
                <div id="bank-info" class="text-sm text-gray-700">
                    <p><strong>Ngân hàng:</strong> <span id="bank-name">VietinBank</span></p>
                    <p><strong>Số tài khoản:</strong> <span id="account-no">100610161104</span></p>
                    <p><strong>Chủ TK:</strong> <span id="account-name">Nguyễn Thiện Nhân</span></p>
                    <p><strong>Số tiền:</strong> <span
                            id="transfer-amount">{{ number_format($order->order_total_after ?? 0) }} VNĐ</span></p>
                    <p><strong>Nội dung:</strong> <span id="transfer-content">DH{{ $order->order_id }}</span></p>
                </div>
            </div>
            <p class="text-sm text-yellow-700 mt-3">Lưu ý: chuyển đúng số tiền và ghi đúng nội dung để hệ thống tự động
                cập nhật trạng thái.</p>
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

            // On load - request QR for this order
            (function generateOrderQR() {
                const orderId = {{ $order->order_id }};
                const amount = {{ $order->order_total_after ?? 0 }};
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/vnpay/generate-qr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ order_id: orderId, amount: amount })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.qr_url) {
                            document.getElementById('qr-code').src = data.qr_url;
                        }
                        if (data.bank_info) {
                            const b = data.bank_info;
                            document.getElementById('bank-name').innerText = b.bank_name || 'N/A';
                            document.getElementById('account-no').innerText = b.account_no || '';
                            document.getElementById('account-name').innerText = b.account_name || '';
                            document.getElementById('transfer-amount').innerText = (b.amount ? b.amount + ' VNĐ' : amount + ' VNĐ');
                            document.getElementById('transfer-content').innerText = b.content || ('DH' + orderId);
                        }
                    })
                    .catch(err => console.error('QR generate error', err));
            })();
        </script>
    </div>
</body>

</html>