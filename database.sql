-- Database Schema for MMO Project
-- Created by Senior Developer (20 years experience)

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT 0.00,
  `remember_token` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(45) DEFAULT NULL,
  `status` enum('active','banned','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `remember_token` (`remember_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Banks table
CREATE TABLE IF NOT EXISTS `banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(100) NOT NULL,
  `bank_code` varchar(20) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `branch` varchar(200) DEFAULT NULL,
  `min_amount` decimal(15,2) DEFAULT 10000.00,
  `max_amount` decimal(15,2) DEFAULT 50000000.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bank_code` (`bank_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Deposits table
CREATE TABLE IF NOT EXISTS `deposits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` enum('card','bank') NOT NULL,
  `status` enum('pending','success','failed','cancelled') DEFAULT 'pending',
  `card_type` varchar(20) DEFAULT NULL,
  `card_code` varchar(50) DEFAULT NULL,
  `card_serial` varchar(50) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password reset tokens table
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default banks
INSERT INTO `banks` (`bank_name`, `bank_code`, `account_name`, `account_number`, `branch`, `min_amount`, `max_amount`) VALUES
('Vietcombank', 'VCB', 'NGUYEN VAN A', '0011001234567', 'Chi nhánh Hà Nội', 10000.00, 50000000.00),
('Techcombank', 'TCB', 'NGUYEN VAN A', '1903123456789', 'Chi nhánh TP.HCM', 10000.00, 50000000.00),
('Vietinbank', 'CTG', 'NGUYEN VAN A', '71123456789', 'Chi nhánh Đà Nẵng', 10000.00, 50000000.00),
('DongA Bank', 'DAB', 'NGUYEN VAN A', '0123456789', 'Chi nhánh Quận 1', 10000.00, 50000000.00),
('ACB Bank', 'ACB', 'NGUYEN VAN A', '23456789', 'Chi nhánh Bình Thạnh', 10000.00, 50000000.00),
('MB Bank', 'MB', 'NGUYEN VAN A', '880123456789', 'Chi nhánh Cầu Giấy', 10000.00, 50000000.00);

-- Insert default settings
INSERT INTO `settings` (`name`, `value`, `description`) VALUES
('site_name', 'MMO Platform', 'Tên website'),
('site_description', 'Nền tảng MMO uy tín hàng đầu', 'Mô tả website'),
('site_keywords', 'mmo, kiếm tiền online, tài chính', 'Từ khóa SEO'),
('card_discount', '20', 'Chiết khấu thẻ cào (%)'),
('min_card_amount', '10000', 'Mệnh giá thẻ cào tối thiểu'),
('max_card_amount', '500000', 'Mệnh giá thẻ cào tối đa'),
('auto_approve_card', '0', 'Tự động duyệt thẻ cào (0/1)'),
('maintenance', '0', 'Chế độ bảo trì (0/1)');

-- Website categories table (Danh mục)
CREATE TABLE IF NOT EXISTS `website_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample website categories
INSERT INTO `website_categories` (`name`, `slug`, `description`, `image`, `sort_order`) VALUES
('Web MMO', 'web-mmo', 'Các website chuyên về MMO, kiếm tiền online, tài chính', 'https://via.placeholder.com/400x300', 1),
('Website Bán Hàng', 'website-ban-hang', 'Website thương mại điện tử, bán hàng trực tuyến', 'https://via.placeholder.com/400x300', 2),
('Website Tin Tức', 'website-tin-tuc', 'Website tin tức, blog, tạp chí trực tuyến', 'https://via.placeholder.com/400x300', 3),
('Website Du Lịch', 'website-du-lich', 'Website du lịch, đặt tour, khách sạn', 'https://via.placeholder.com/400x300', 4),
('Website Giáo Dục', 'website-giao-duc', 'Website giáo dục, học trực tuyến, trung tâm', 'https://via.placeholder.com/400x300', 5),
('Website Y Tế', 'website-y-te', 'Website y tế, phòng khám, bệnh viện', 'https://via.placeholder.com/400x300', 6);

-- Website interfaces table (Giao diện)
CREATE TABLE IF NOT EXISTS `website_interfaces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `discount_3_months` decimal(5,2) DEFAULT 5.00,
  `discount_6_months` decimal(5,2) DEFAULT 10.00,
  `discount_12_months` decimal(5,2) DEFAULT 20.00,
  `image` varchar(500) DEFAULT NULL,
  `demo_url` varchar(500) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `category` (`category`),
  KEY `status` (`status`),
  KEY `is_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Website orders table (Đơn hàng)
CREATE TABLE IF NOT EXISTS `website_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `interface_id` int(11) NOT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `dot_id` int(11) DEFAULT NULL,
  `months` int(11) DEFAULT 1,
  `price` decimal(15,2) NOT NULL,
  `status` enum('pending','processing','active','expired','cancelled') DEFAULT 'pending',
  `order_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `expiry_date` timestamp NULL DEFAULT NULL,
  `domain_expiry_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `interface_id` (`interface_id`),
  KEY `dot_id` (`dot_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Domain dots table (Đuôi miền)
CREATE TABLE IF NOT EXISTS `dots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `renewal_price` decimal(15,2) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample website interfaces
INSERT INTO `website_interfaces` (`category_id`, `name`, `description`, `category`, `price`, `sale_price`, `discount_3_months`, `discount_6_months`, `discount_12_months`, `image`, `demo_url`, `features`, `is_featured`) VALUES
(1, 'MÃ NGUỒN AI GENERATE V1', 'Mã nguồn website AI Generator phiên bản 1 với đầy đủ tính năng', 'WEB MMO', 500000.00, 200000.00, 5.00, 10.00, 20.00, 'https://dacy.vn/anh/51d79d8cfd2804abaffa1077d7e465d4.png', 'https://demo.example.com', 'AI Generator, Responsive, Admin Panel, Payment Integration', 1),
(2, 'WEBSITE BÁN HÀNG', 'Website bán hàng chuyên nghiệp với giỏ hàng và thanh toán', 'E-COMMERCE', 800000.00, 400000.00, 3.00, 8.00, 15.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Shopping Cart, Payment, Inventory, Admin Dashboard', 1),
(3, 'WEBSITE TIN TỨC', 'Website tin tức, blog với quản lý bài viết chuyên nghiệp', 'NEWS', 600000.00, 300000.00, 5.00, 12.00, 25.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Article Management, Categories, Comments, SEO', 0),
(4, 'WEBSITE DU LỊCH', 'Website du lịch, đặt tour với hệ thống quản lý tour', 'TRAVEL', 1200000.00, 800000.00, 7.00, 15.00, 30.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Tour Management, Booking, Payment, Gallery', 0),
(1, 'WEBSITE INVESTMENT', 'Website đầu tư, tài chính với nhiều tính năng chuyên nghiệp', 'WEB MMO', 1500000.00, 1000000.00, 10.00, 20.00, 35.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Investment Plans, Payment Gateway, User Dashboard, Analytics', 1),
(5, 'WEBSITE GIÁO DỤC', 'Website giáo dục, học trực tuyến với quản lý khóa học', 'EDUCATION', 900000.00, 600000.00, 4.00, 9.00, 18.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Course Management, Video Lessons, Student Dashboard, Certificates', 0);

-- Insert sample domain dots
INSERT INTO `dots` (`name`, `price`, `renewal_price`, `description`) VALUES
('.com', 250000.00, 300000.00, 'Đuôi miền quốc tế phổ biến nhất'),
('.vn', 650000.00, 700000.00, 'Đuôi miền quốc gia Việt Nam'),
('.net', 300000.00, 350000.00, 'Đuôi miền quốc tế phổ biến'),
('.org', 350000.00, 400000.00, 'Đuôi miền cho tổ chức'),
('.info', 200000.00, 250000.00, 'Đuôi miền thông tin'),
('.biz', 280000.00, 320000.00, 'Đuôi miền kinh doanh');

-- Transaction logs table
CREATE TABLE IF NOT EXISTS `transaction_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('success','failed','pending') DEFAULT 'success',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Renewal history table
CREATE TABLE IF NOT EXISTS `renewal_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `months` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `renew_domain` tinyint(1) DEFAULT 0,
  `domain_amount` decimal(15,2) DEFAULT 0.00,
  `hosting_amount` decimal(15,2) DEFAULT 0.00,
  `old_expiry_date` timestamp NULL DEFAULT NULL,
  `new_expiry_date` timestamp NULL DEFAULT NULL,
  `old_domain_expiry_date` timestamp NULL DEFAULT NULL,
  `new_domain_expiry_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;