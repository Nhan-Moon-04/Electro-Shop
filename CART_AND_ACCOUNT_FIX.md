# Hướng dẫn sửa lỗi Giỏ hàng và Thông tin Tài khoản

## Vấn đề đã phát hiện

### 1. Lỗi thêm sản phẩm vào giỏ hàng

**Nguyên nhân:** Khi user đăng ký mới, hệ thống chỉ tạo bản ghi trong bảng `users` mà không tạo bản ghi tương ứng trong bảng `customers`. Khi thêm sản phẩm vào giỏ hàng, API không tìm thấy `customer_id` nên báo lỗi.

### 2. Thông tin tài khoản

API `/api/auth/me` hoạt động bình thường vì chỉ lấy dữ liệu từ bảng `users`.

## Các thay đổi đã thực hiện

### 1. Sửa AuthController (app/Http/Controllers/Api/AuthController.php)

-   Thêm import `Customer` model và `DB` facade
-   Sửa phương thức `register()` để tự động tạo bản ghi `customers` khi đăng ký
-   Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
-   Cải thiện error handling

**Code thay đổi:**

```php
// Thêm imports
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

// Trong hàm register(), thêm code tạo customer
DB::beginTransaction();

try {
    // Tạo user
    $user = User::create([...]);

    // Tạo customer tương ứng
    Customer::create([
        'customer_id' => $user->user_id,
        'user_id' => $user->user_id
    ]);

    // Tạo token xác thực email
    // Gửi email

    DB::commit();
    return response()->json([...], 201);

} catch (\Exception $e) {
    DB::rollBack();
    Log::error('Registration failed: ' . $e->getMessage());
    return response()->json(['error' => 'Đăng ký thất bại. Vui lòng thử lại sau.'], 500);
}
```

### 2. Cải thiện Error Handling trong JavaScript

Cập nhật các file sau với error handling tốt hơn:

-   `resources/views/home.blade.php`
-   `resources/views/products/index.blade.php`
-   `resources/views/products/show.blade.php`

**Cải thiện:**

-   Kiểm tra HTTP status code (401 cho unauthorized)
-   Hiển thị thông báo lỗi chi tiết hơn
-   Xử lý phiên đăng nhập hết hạn
-   Log lỗi vào console để dễ debug

**Ví dụ code:**

```javascript
fetch('/api/cart/add', {...})
    .then(response => {
        if (response.status === 401) {
            alert('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!');
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
            return null;
        }
        return response.json();
    })
    .then(data => {
        if (!data) return;

        if (data.success) {
            // Xử lý thành công
        } else {
            alert(data.message || data.error || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Không thể thêm vào giỏ hàng. Vui lòng thử lại!');
    });
```

### 3. Script cập nhật dữ liệu cho users hiện có

Tạo file `scripts/create_missing_customers.php` để tự động tạo bản ghi `customers` cho các user đã đăng ký trước đó.

**Chạy script:**

```bash
php scripts/create_missing_customers.php
```

**Kết quả:**

-   Đã tạo thành công 3 customer records cho users: 14, 15, 37
-   Users 11, 12, 13 đã có customer records từ trước

## Kiểm tra kết quả

### 1. Test đăng ký user mới

1. Truy cập trang đăng ký: `/register`
2. Nhập thông tin và đăng ký
3. Kiểm tra database:
    - Bảng `users`: có bản ghi mới
    - Bảng `customers`: có bản ghi tương ứng với cùng `user_id`
    - Bảng `email_verification_tokens`: có token xác thực

### 2. Test thêm sản phẩm vào giỏ hàng

1. Đăng nhập với tài khoản đã xác thực
2. Thêm sản phẩm vào giỏ hàng
3. Kiểm tra:
    - Không có lỗi "Customer not found"
    - Sản phẩm được thêm vào bảng `carts`
    - Số lượng giỏ hàng cập nhật đúng

### 3. Test thông tin tài khoản

1. Đăng nhập và truy cập `/account/profile`
2. Kiểm tra:
    - Thông tin hiển thị đúng (tên, email)
    - Có thể cập nhật thông tin
    - Có thể đăng xuất

## Lưu ý quan trọng

1. **Migration:** Đã có migration `2024_01_26_000002_create_email_verification_tokens_table.php` và đã chạy thành công

2. **Email Verification:** Hệ thống gửi email xác thực khi đăng ký. Nếu gửi email thất bại:

    - User vẫn được tạo thành công
    - Lỗi được log vào `storage/logs/laravel.log`
    - Cần kiểm tra cấu hình SMTP trong `.env`

3. **Database Integrity:** Sử dụng transaction để đảm bảo:
    - Nếu tạo user thành công nhưng tạo customer thất bại → rollback toàn bộ
    - Không tạo ra dữ liệu không nhất quán

## Checklist hoàn thành

-   [x] Sửa AuthController để tự động tạo customer
-   [x] Cải thiện error handling trong JavaScript
-   [x] Tạo script cập nhật dữ liệu cho users cũ
-   [x] Chạy script và verify kết quả
-   [x] Test đăng ký user mới
-   [x] Test thêm sản phẩm vào giỏ hàng
-   [x] Test thông tin tài khoản

## Các file đã thay đổi

1. `app/Http/Controllers/Api/AuthController.php` - Sửa logic đăng ký
2. `resources/views/home.blade.php` - Cải thiện error handling
3. `resources/views/products/index.blade.php` - Cải thiện error handling
4. `resources/views/products/show.blade.php` - Cải thiện error handling
5. `scripts/create_missing_customers.php` - Script cập nhật dữ liệu (mới)
6. `CART_AND_ACCOUNT_FIX.md` - Tài liệu này (mới)
