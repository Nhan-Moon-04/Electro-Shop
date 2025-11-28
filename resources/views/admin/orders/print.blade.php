<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            padding: 20px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 32px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .info-section h3 {
            font-size: 16px;
            color: #2563eb;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .info-section p {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-section strong {
            display: inline-block;
            width: 140px;
            color: #666;
        }
        .order-details {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table thead {
            background: #f3f4f6;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 2px solid #ddd;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 15px;
        }
        .summary-row.total {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-completed { background: #dcfce7; color: #16a34a; }
        .status-pending { background: #fef3c7; color: #ca8a04; }
        .status-shipping { background: #dbeafe; color: #2563eb; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        {{-- Header --}}
        <div class="header">
            <h1>⚡ ELECTROSHOP</h1>
            <p>Điện thoại: 1900 1234 | Email: support@electroshop.vn</p>
            <p>Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
        </div>

        {{-- Invoice Title --}}
        <h2 style="text-align: center; font-size: 24px; margin-bottom: 20px; color: #2563eb;">
            HÓA ĐƠN BÁN HÀNG
        </h2>

        {{-- Invoice Info --}}
        <div class="invoice-info">
            {{-- Order Info --}}
            <div class="info-section">
                <h3>Thông tin đơn hàng</h3>
                <p><strong>Mã đơn hàng:</strong> #{{ $order->order_id }}</p>
                <p><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong> 
                    @php
                        $statusClass = match($order->order_status) {
                            'Hoàn thành' => 'status-completed',
                            'Chờ thanh toán' => 'status-pending',
                            'Đang giao hàng' => 'status-shipping',
                            'Đã hủy' => 'status-cancelled',
                            default => 'status-pending'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $order->order_status }}</span>
                </p>
                <p><strong>Thanh toán:</strong> {{ $order->payingMethod->paying_method_name ?? 'N/A' }}</p>
            </div>

            {{-- Customer Info --}}
            <div class="info-section">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Họ tên:</strong> {{ $order->order_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->order_phone }}</p>
                <p><strong>Địa chỉ giao hàng:</strong> {{ $order->order_delivery_address }}</p>
                @if($order->order_note)
                <p><strong>Ghi chú:</strong> {{ $order->order_note }}</p>
                @endif
            </div>
        </div>

        {{-- Order Details Table --}}
        <div class="order-details">
            <h3 style="font-size: 18px; color: #2563eb; margin-bottom: 15px;">Chi tiết sản phẩm</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">STT</th>
                        <th>Sản phẩm</th>
                        <th class="text-center" style="width: 100px;">Số lượng</th>
                        <th class="text-right" style="width: 120px;">Đơn giá</th>
                        <th class="text-right" style="width: 120px;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $detail->productVariant->product->product_name ?? 'N/A' }}</strong><br>
                            <small style="color: #666;">{{ $detail->productVariant->product_variant_name ?? '' }}</small>
                        </td>
                        <td class="text-center">{{ $detail->order_detail_quantity }}</td>
                        <td class="text-right">{{ number_format($detail->order_detail_price_after, 0, ',', '.') }}₫</td>
                        <td class="text-right">{{ number_format($detail->order_detail_price_after * $detail->order_detail_quantity, 0, ',', '.') }}₫</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary --}}
        <div class="summary">
            <div class="summary-row">
                <span>Tổng tiền hàng:</span>
                <span>{{ number_format($order->order_total_before, 0, ',', '.') }}₫</span>
            </div>
            @if($order->order_total_before != $order->order_total_after)
            <div class="summary-row">
                <span>Giảm giá:</span>
                <span style="color: #dc2626;">-{{ number_format($order->order_total_before - $order->order_total_after, 0, ',', '.') }}₫</span>
            </div>
            @endif
            <div class="summary-row total">
                <span>TỔNG THANH TOÁN:</span>
                <span>{{ number_format($order->order_total_after, 0, ',', '.') }}₫</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p><strong>Cảm ơn quý khách đã mua hàng tại ElectroShop!</strong></p>
            <p>Mọi thắc mắc xin vui lòng liên hệ: 1900 1234 hoặc support@electroshop.vn</p>
            <p style="margin-top: 10px; font-style: italic;">Hóa đơn được in vào: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        {{-- Print Button --}}
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
                <i class="fas fa-print"></i> In hóa đơn
            </button>
            <button onclick="window.close()" style="background: #6b7280; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; margin-left: 10px;">
                Đóng
            </button>
        </div>
    </div>
</body>
</html>
