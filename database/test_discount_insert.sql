-- Test insert discount vào database
-- Chạy file này trong phpMyAdmin hoặc MySQL client để test

INSERT INTO `discounts` 
(`discount_name`, `discount_description`, `discount_start_date`, `discount_end_date`, `discount_amount`, `discount_is_display`) 
VALUES
('Test Khuyến Mãi', 'Đây là khuyến mãi test', '2025-11-18', '2025-12-31', 15, 1);

-- Kiểm tra kết quả
SELECT * FROM `discounts` ORDER BY `discount_id` DESC LIMIT 1;
