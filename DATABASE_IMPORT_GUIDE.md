# Hướng Dẫn Setup Database - Terminal Commands

## BƯỚC 1: Kiểm tra bảng password_reset_tokens

```bash
php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasTable('password_reset_tokens') ? 'yes' : 'no';"
```

## BƯỚC 2: Kiểm tra migration table

```bash
php artisan tinker --execute="print_r(\DB::table('migrations')->orderBy('id')->get()->toArray());"
```

## BƯỚC 3: Chạy mark migration script

```bash
php scripts/mark_migration.php
```

## BƯỚC 4: Kiểm tra migration status

```bash
php artisan migrate:status
```

## BƯỚC 5: Chạy migration force

```bash
php artisan migrate --force
```

## BƯỚC 6: Thêm migration record cho password_reset_tokens

```bash
php artisan tinker --execute="$max = DB::table('migrations')->max('batch'); DB::table('migrations')->insert(['migration'=>'2025_10_19_000001_create_password_reset_tokens_table','batch'=>($max ? $max : 1)]); echo 'inserted';"
```

## BƯỚC 7: Chạy script tạo password reset table

```bash
php scripts/create_password_reset_table.php
```

## BƯỚC 8: Kiểm tra migration status lần nữa

```bash
php artisan migrate:status
```

## BƯỚC 9: Chạy migration cuối cùng

```bash
php artisan migrate --force
```

## KIỂM TRA KẾT QUỢ

### Verify bảng password_reset_tokens đã tạo

```bash
php artisan tinker --execute="echo 'Table exists: ' . (Schema::hasTable('password_reset_tokens') ? 'YES' : 'NO');"
```

### Kiểm tra migration status cuối cùng

```bash
php artisan migrate:status
```

## CHẠY TẤT CẢ LỆNH TUẦN TỰ (Copy & Paste)

```bash
php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasTable('password_reset_tokens') ? 'yes' : 'no';"
php artisan tinker --execute="print_r(\DB::table('migrations')->orderBy('id')->get()->toArray());"
php scripts/mark_migration.php
php artisan migrate:status
php artisan migrate --force
php artisan tinker --execute="$max = DB::table('migrations')->max('batch'); DB::table('migrations')->insert(['migration'=>'2025_10_19_000001_create_password_reset_tokens_table','batch'=>($max ? $max : 1)]); echo 'inserted';"
php scripts/create_password_reset_table.php
php artisan migrate:status
php artisan migrate --force
```

## LỆNH KIỂM TRA CUỐI

```bash
php artisan tinker --execute="echo 'Table exists: ' . (Schema::hasTable('password_reset_tokens') ? 'YES' : 'NO');"
php artisan migrate:status
```

## XỬ LÝ LỖI

### Nếu "Migration table not found":

```bash
php artisan migrate --force
```

### Nếu "Table already exists":

➡️ Bỏ qua, tiếp tục bước tiếp theo

### Nếu Parse error trong tinker:

```bash
php artisan tinker --execute="echo 'Simple check';"
```

### Clear cache nếu có lỗi:

```bash
php artisan config:clear
php artisan cache:clear
```

## Cấu Trúc Bảng password_reset_tokens

```sql
CREATE TABLE `password_reset_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `MaNguoiDung` INT UNSIGNED NOT NULL,
  `Token` VARCHAR(100) NOT NULL,
  `ExpireAt` DATETIME NOT NULL,
  `Used` TINYINT(1) NOT NULL DEFAULT 0,
  INDEX (`MaNguoiDung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Xử Lý Lỗi Thường Gặp

### Lỗi 1: "Migration table not found"

**Giải pháp**: Chạy `php artisan migrate --force` để tự động tạo migration table.

### Lỗi 2: "Table already exists"

**Giải pháp**: Bảng đã tồn tại, bỏ qua lệnh tạo bảng và tiếp tục.

### Lỗi 3: Doctrine DBAL compatibility issue

**Giải pháp**: Lỗi này không ảnh hưởng đến bảng password_reset_tokens. Có thể bỏ qua.

### Lỗi 4: Parse error trong tinker

**Giải pháp**: Sử dụng lệnh đơn giản hơn hoặc tạo file script riêng.

## Kiểm Tra Cuối Cùng

### Verify bảng đã tạo thành công

```bash
php artisan tinker --execute="echo 'Table exists: ' . (Schema::hasTable('password_reset_tokens') ? 'YES' : 'NO');"
```

### Kiểm tra danh sách migration

```bash
php artisan migrate:status
```

## Script Commands Đầy Đủ

Chạy tuần tự các lệnh sau:

```bash
# 1. Kiểm tra bảng password_reset_tokens
php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasTable('password_reset_tokens') ? 'yes' : 'no';"

# 2. Kiểm tra migration table
php artisan tinker --execute="print_r(\DB::table('migrations')->orderBy('id')->get()->toArray());"

# 3. Chạy mark migration script
php scripts/mark_migration.php

# 4. Kiểm tra migrate status
php artisan migrate:status

# 5. Chạy migration
php artisan migrate --force

# 6. Thêm migration record
php artisan tinker --execute="$max = DB::table('migrations')->max('batch'); DB::table('migrations')->insert(['migration'=>'2025_10_19_000001_create_password_reset_tokens_table','batch'=>($max ? $max : 1)]); echo 'inserted';"

# 7. Chạy script tạo bảng
php scripts/create_password_reset_table.php

# 8. Kiểm tra status cuối
php artisan migrate:status

# 9. Chạy migration cuối cùng
php artisan migrate --force
```

## Kết Quả Mong Đợi

Sau khi hoàn thành, bạn sẽ có:

-   ✅ Migration table được tạo
-   ✅ Bảng `password_reset_tokens` với cấu trúc đúng
-   ✅ Các migration records trong database
-   ✅ Hệ thống migration hoạt động bình thường

## Troubleshooting

### Nếu migration bị stuck

1. Kiểm tra database connection trong `.env`
2. Xóa các file cache: `php artisan config:clear`
3. Khởi động lại web server

### Nếu cần reset migration

⚠️ **CẢNH BÁO**: Chỉ sử dụng trong môi trường development

```bash
php artisan migrate:reset
php artisan migrate --force
```

## Liên Hệ Support

-   Kiểm tra file logs trong `storage/logs/`
-   Báo cáo issues trên repository GitHub
-   Liên hệ team development để được hỗ trợ

---

_Cập nhật lần cuối: November 8, 2025_
