<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'amount' => 'required|numeric|min:1000',
        ]);

        $order = Order::where('order_id', $request->order_id)->first();
        if (!$order) {
            return response()->json(['error' => 'Đơn hàng không tồn tại'], 404);
        }

        // Thông tin cấu hình VNPay từ config
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_ReturnUrl = config('vnpay.return_url');

        // Thông tin giao dịch
        $vnp_TxnRef = $order->order_id . '_' . time(); // Mã giao dịch duy nhất
        $vnp_OrderInfo = 'Thanh toan don hang #' . $order->order_id;
        $vnp_OrderType = config('vnpay.order_type');
        $vnp_Amount = $request->amount * 100; // VNPay yêu cầu amount * 100
        $vnp_Locale = config('vnpay.locale');
        $vnp_IpAddr = $request->ip();

        $inputData = array(
            "vnp_Version" => config('vnpay.version'),
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => config('vnpay.command'),
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => config('vnpay.currency'),
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Lưu thông tin giao dịch vào database hoặc session nếu cần
        session(['vnpay_order_id' => $order->order_id]);

        return response()->json([
            'success' => true,
            'payment_url' => $vnp_Url,
            'order_id' => $order->order_id
        ]);
    }

    /**
     * Xử lý kết quả trả về từ VNPay
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');

        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Lấy order_id từ vnp_TxnRef
        $txnRef = $request->vnp_TxnRef;
        $orderId = explode('_', $txnRef)[0];

        if ($secureHash == $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                // Thanh toán thành công
                $order = Order::where('order_id', $orderId)->first();
                if ($order) {
                    $order->update([
                        'order_is_paid' => 1,
                        'order_paying_date' => date('Y-m-d H:i:s'),
                        'order_status' => 'Đã thanh toán',
                        'paying_method_id' => 1 // VNPay
                    ]);
                }

                return view('payment.success', [
                    'order' => $order,
                    'message' => 'Thanh toán thành công!'
                ]);
            } else {
                // Thanh toán thất bại
                return view('payment.failed', [
                    'message' => 'Thanh toán thất bại! Mã lỗi: ' . $request->vnp_ResponseCode
                ]);
            }
        } else {
            // Chữ ký không hợp lệ
            return view('payment.failed', [
                'message' => 'Chữ ký không hợp lệ!'
            ]);
        }
    }

    /**
     * Webhook IPN từ VNPay (tự động xác nhận thanh toán)
     */
    public function vnpayIPN(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');

        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $returnData = array();

        if ($secureHash == $vnp_SecureHash) {
            // Lấy order_id từ vnp_TxnRef
            $txnRef = $request->vnp_TxnRef;
            $orderId = explode('_', $txnRef)[0];

            $order = Order::where('order_id', $orderId)->first();

            if ($order) {
                if ($request->vnp_ResponseCode == '00') {
                    // Thanh toán thành công
                    if ($order->order_is_paid == 0) {
                        $order->update([
                            'order_is_paid' => 1,
                            'order_paying_date' => date('Y-m-d H:i:s'),
                            'order_status' => 'Đã thanh toán'
                        ]);
                    }
                    $returnData['RspCode'] = '00';
                    $returnData['Message'] = 'Confirm Success';
                } else {
                    // Thanh toán thất bại
                    $returnData['RspCode'] = '02';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '02';
                $returnData['Message'] = 'Order not found';
            }
        } else {
            $returnData['RspCode'] = '97';
            $returnData['Message'] = 'Invalid signature';
        }

        Log::info('VNPay IPN Response: ', $returnData);
        return response()->json($returnData);
    }

    /**
     * Tạo QR Code thanh toán ngân hàng
     */
    public function generateQR(Request $request)
    {
        $amount = $request->amount ?? 0;
        $orderId = $request->order_id ?? '';

        // Tạo nội dung chuyển khoản 
        $addInfo = "DH" . $orderId; // Rút gọn để tránh quá dài

        // Thông tin ngân hàng từ config
        $bankInfo = config('vnpay.bank_info');
        $bankId = $bankInfo['bank_id'];     // 970415 - VietinBank
        $accountNo = $bankInfo['account_no']; // 100610161104
        $template = "compact2";

        // Tạo QR URL với VietQR API
        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-{$template}.png?amount={$amount}&addInfo={$addInfo}&accountName=" . urlencode($bankInfo['account_name']);

        return response()->json([
            'qr_url' => $qrUrl,
            'bank_info' => [
                'bank_name' => $bankInfo['bank_name'],
                'account_no' => $accountNo,
                'account_name' => $bankInfo['account_name'],
                'amount' => number_format($amount),
                'content' => $addInfo,
                'note' => 'Quét mã QR hoặc chuyển khoản thủ công với nội dung: ' . $addInfo
            ]
        ]);
    }
}