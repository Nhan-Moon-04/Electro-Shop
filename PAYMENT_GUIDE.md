# 🛒 Hướng dẫn sử dụng hệ thống thanh toán ElectroShop

## 📝 **Tóm tắt thay đổi**

Đã **XÓA BỎ HOÀN TOÀN** chức năng giỏ hàng và chỉ giữ lại chức năng **"Mua ngay"** đơn giản.

## ✅ **Files đã xóa:**

-   `app/Http/Controllers/CartController.php` - ❌ Đã xóa

## ✅ **Files đã cập nhật:**

### 1. **Routes (`routes/web.php`)**

```php
// ❌ Đã bỏ: Route giỏ hàng
// ✅ Chỉ còn: Route checkout cho "mua ngay"
Route::get('/checkout', [PaymentController::class, 'showCheckout'])->name('checkout.index');
```

### 2. **PaymentController**

```php
// ❌ Đã bỏ: showCheckoutCart() method
// ✅ Chỉ còn: showCheckout() cho mua 1 sản phẩm
```

### 3. **Trang sản phẩm (`resources/views/products/show.blade.php`)**

```html
<!-- ❌ Đã bỏ: Nút "Thêm vào giỏ hàng" -->
<!-- ✅ Chỉ còn: Nút "Mua ngay" -->
<button id="buyNowBtn">Mua ngay - Giao hàng trong 1 giờ</button>
```

### 4. **Trang checkout (`resources/views/checkout/index.blade.php`)**

```php
// ❌ Đã bỏ: Logic xử lý giỏ hàng (cart)
// ✅ Chỉ còn: Logic xử lý 1 sản phẩm (single)
```

## 🚀 **Workflow mới (đơn giản):**

```
Trang sản phẩm → Chọn số lượng → Nhấn "Mua ngay"
    ↓
Trang thanh toán → Điền thông tin → Chọn phương thức thanh toán
    ↓
Thanh toán VNPay / QR / COD → Hoàn tất đơn hàng
```

## ⚙️ **Cách sử dụng:**

### **Bước 1: Trên trang sản phẩm**

1. Chọn số lượng sản phẩm muốn mua
2. Nhấn nút **"Mua ngay - Giao hàng trong 1 giờ"**

### **Bước 2: Trên trang checkout**

1. Điền thông tin giao hàng:

    - Họ tên **(bắt buộc)**
    - Số điện thoại **(bắt buộc)**
    - Email
    - Địa chỉ giao hàng **(bắt buộc)**
    - Ghi chú đơn hàng

2. Chọn phương thức thanh toán:

    - **VNPay** - Thanh toán qua ngân hàng
    - **QR Code** - Chuyển khoản quét mã QR
    - **COD** - Thanh toán khi nhận hàng

3. Nhấn **"Đặt hàng"**

### **Bước 3: Thanh toán**

#### **🏦 VNPay:**

-   Chuyển đến trang VNPay → Nhập thông tin thẻ/tài khoản → Xác nhận
-   Tự động quay về với kết quả thành công/thất bại

#### **📱 QR Code:**

-   Quét mã QR hiển thị trên màn hình
-   Chuyển khoản theo thông tin:
    -   **Ngân hàng:** VietinBank
    -   **STK:** 100610161104
    -   **Chủ TK:** Nguyen Thien Nhan
-   Nhấn "Xác nhận đã thanh toán"

#### **💵 COD:**

-   Đặt hàng thành công
-   Thanh toán tiền mặt khi shipper giao hàng

## 📁 **Cấu trúc Files chính:**

```
app/Http/Controllers/
├── PaymentController.php     # Xử lý checkout & đơn hàng
├── VNPayController.php       # Xử lý thanh toán VNPay
└── ❌ CartController.php     # ĐÃ XÓA

resources/views/
├── checkout/index.blade.php  # Trang thanh toán
├── payment/
│   ├── success.blade.php     # Thanh toán thành công
│   ├── failed.blade.php      # Thanh toán thất bại
│   └── payment_page.blade.php # Trang đơn hàng
└── products/show.blade.php   # Trang chi tiết sản phẩm

config/vnpay.php              # Cấu hình VNPay
.env                          # Biến môi trường VNPay
```

## 🔧 **Routes chính:**

```php
# Trang thanh toán
GET  /checkout                → PaymentController@showCheckout

# Tạo đơn hàng
POST /create-order           → PaymentController@createOrder

# VNPay
POST /vnpay/create-payment   → VNPayController@createPayment
GET  /vnpay/return          → VNPayController@vnpayReturn
POST /vnpay/ipn             → VNPayController@vnpayIPN
POST /vnpay/generate-qr     → VNPayController@generateQR

# Xem đơn hàng
GET  /payment/{orderId}     → PaymentController@showPayment
```

## 🎯 **Ưu điểm của workflow mới:**

✅ **Đơn giản hơn** - Không cần quản lý giỏ hàng phức tạp  
✅ **Nhanh hơn** - Mua ngay không qua nhiều bước  
✅ **Dễ bảo trì** - Ít code hơn, ít bug hơn  
✅ **UX tốt** - Phù hợp với mua sắm nhanh

## ⚠️ **Lưu ý:**

-   Nếu muốn mua nhiều sản phẩm khác nhau → Phải mua từng sản phẩm một
-   Đã loại bỏ hoàn toàn session cart và localStorage
-   Tất cả chức năng liên quan đến giỏ hàng đã bị xóa

---

**✨ Hệ thống bây giờ đã sạch sẽ và chỉ tập trung vào "Mua ngay" đơn giản!**
