<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPay Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for VNPay payment gateway
    |
    */

    'tmn_code' => env('VNP_TMN_CODE', 'DEMO_TMN_CODE'),
    'hash_secret' => env('VNP_HASH_SECRET', 'DEMO_HASH_SECRET'),
    'url' => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'api_url' => env('VNP_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),

    // Return URL sau khi thanh toán
    'return_url' => env('APP_URL', 'http://localhost') . '/vnpay/return',

    // Các loại currency được hỗ trợ
    'currency' => 'VND',

    // Phiên bản API
    'version' => '2.1.0',

    // Locale
    'locale' => 'vn',

    // Command
    'command' => 'pay',

    // Order type
    'order_type' => 'other',

    // Timeout (phút)
    'timeout' => 15,

    // Thông tin ngân hàng cho QR Code
    'bank_info' => [
        'bank_id' => '970415', // VietinBank
        'account_no' => '100610161104',
        'account_name' => 'Nguyen Thien Nhan',
        'bank_name' => 'VietinBank'
    ]
];