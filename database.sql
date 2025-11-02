-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th10 02, 2025 lúc 05:40 AM
-- Phiên bản máy phục vụ: 10.11.11-MariaDB-cll-lve
-- Phiên bản PHP: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `arownmqdn9q_dbdemo`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_code` varchar(20) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `branch` varchar(200) DEFAULT NULL,
  `min_amount` decimal(15,2) DEFAULT 10000.00,
  `max_amount` decimal(15,2) DEFAULT 50000000.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banks`
--

INSERT INTO `banks` (`id`, `bank_name`, `bank_code`, `account_name`, `account_number`, `branch`, `min_amount`, `max_amount`, `status`, `created_at`) VALUES
(1, 'Vietcombank', 'VCB', 'NGUYEN VAN A', '0011001234567', 'Chi nhánh Hà Nội', 10000.00, 50000000.00, 'active', '2025-11-01 08:41:49'),
(2, 'Techcombank', 'TCB', 'NGUYEN VAN A', '1903123456789', 'Chi nhánh TP.HCM', 10000.00, 50000000.00, 'active', '2025-11-01 08:41:49'),
(3, 'Vietinbank', 'CTG', 'NGUYEN VAN A', '71123456789', 'Chi nhánh Đà Nẵng', 10000.00, 50000000.00, 'active', '2025-11-01 08:41:49'),
(4, 'DongA Bank', 'DAB', 'NGUYEN VAN A', '0123456789', 'Chi nhánh Quận 1', 10000.00, 50000000.00, 'active', '2025-11-01 08:41:49'),
(5, 'ACB Bank', 'ACB', 'NGUYEN VAN A', '23456789', 'Chi nhánh Bình Thạnh', 10000.00, 50000000.00, 'active', '2025-11-01 08:41:49'),
(6, 'MB Bank', 'MB', 'NGUYEN VAN A', '880123456789', 'Chi nhánh Cầu Giấy', 10000.00, 50000000.00, 'active', '2025-11-01 08:41:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dots`
--

CREATE TABLE `dots` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `renewal_price` decimal(15,2) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dots`
--

INSERT INTO `dots` (`id`, `name`, `price`, `renewal_price`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, '.com', 250000.00, 300000.00, 'active', 'Đuôi miền quốc tế phổ biến nhất', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(2, '.vn', 650000.00, 700000.00, 'active', 'Đuôi miền quốc gia Việt Nam', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(3, '.net', 300000.00, 350000.00, 'active', 'Đuôi miền quốc tế phổ biến', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(4, '.org', 350000.00, 400000.00, 'active', 'Đuôi miền cho tổ chức', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(5, '.info', 200000.00, 250000.00, 'active', 'Đuôi miền thông tin', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(6, '.biz', 280000.00, 280000.00, 'active', 'Đuôi miền kinh doanh', '2025-11-01 08:41:49', '2025-11-01 16:07:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `renewal_history`
--

CREATE TABLE `renewal_history` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `renewal_history`
--

INSERT INTO `renewal_history` (`id`, `order_id`, `user_id`, `months`, `amount`, `renew_domain`, `domain_amount`, `hosting_amount`, `old_expiry_date`, `new_expiry_date`, `old_domain_expiry_date`, `new_domain_expiry_date`, `created_at`) VALUES
(1, 2, 1, 1, 480000.00, 1, 280000.00, 200000.00, '2025-12-01 09:13:12', '2026-01-01 09:13:12', '2025-10-02 09:13:12', '2026-10-02 09:13:12', '2025-11-01 16:25:02'),
(2, 2, 1, 1, 200000.00, 0, 0.00, 200000.00, '2026-01-01 09:13:12', '2026-02-01 09:13:12', '2026-10-02 09:13:12', '2026-10-02 09:13:12', '2025-11-01 16:33:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'MMO Platform', 'Tên website', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(2, 'site_description', 'Nền tảng MMO uy tín hàng đầu', 'Mô tả website', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(3, 'site_keywords', 'mmo, kiếm tiền online, tài chính', 'Từ khóa SEO', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(4, 'card_discount', '20', 'Chiết khấu thẻ cào (%)', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(5, 'min_card_amount', '10000', 'Mệnh giá thẻ cào tối thiểu', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(6, 'max_card_amount', '500000', 'Mệnh giá thẻ cào tối đa', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(7, 'auto_approve_card', '0', 'Tự động duyệt thẻ cào (0/1)', '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(8, 'maintenance', '0', 'Chế độ bảo trì (0/1)', '2025-11-01 08:41:49', '2025-11-01 08:41:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transaction_logs`
--

CREATE TABLE `transaction_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('success','failed','pending') DEFAULT 'success',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `transaction_logs`
--

INSERT INTO `transaction_logs` (`id`, `user_id`, `amount`, `type`, `description`, `status`, `created_at`) VALUES
(1, 1, 850000.00, 'website_order', 'Thuê website vpsre.vn - 1 tháng - Giao diện: MÃ NGUỒN AI GENERATE V1', 'success', '2025-11-01 08:48:30'),
(2, 1, 480000.00, 'website_order', 'Thuê website shopsieugame.biz - 1 tháng - Giao diện: MÃ NGUỒN AI GENERATE V1', 'success', '2025-11-01 09:13:12'),
(3, 1, 480000.00, 'renewal', 'Gia hạn hosting 1 tháng và tên miền (1 năm) cho website shopsieugame.biz [Tự động gia hạn tên miền - Còn -2 tháng]', 'success', '2025-11-01 16:25:02'),
(4, 1, 200000.00, 'renewal', 'Gia hạn hosting 1 tháng cho website shopsieugame.biz [Tự động gia hạn tên miền - Còn 11 tháng]', 'success', '2025-11-01 16:33:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `balance` int(255) DEFAULT 0,
  `remember_token` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip` varchar(45) DEFAULT NULL,
  `status` enum('active','banned','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `balance`, `remember_token`, `email_verified_at`, `created_at`, `updated_at`, `ip`, `status`) VALUES
(1, 'nhthanh123', 'nhthanh@gmail.com', '$2y$10$hK5hu.ZURy5nDOf.U9DG6uv2W3BYaNE2dwmGYsf7b6dn04mSSJPa2', '0872342343', 0, 'b3ce04fa6bd4aeaf3e27e0667e0f0112', NULL, '0000-00-00 00:00:00', '2025-11-02 05:25:03', '2402:800:63e5:4094:4deb:80df:f0e4:4b0a', 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `website_categories`
--

CREATE TABLE `website_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `website_categories`
--

INSERT INTO `website_categories` (`id`, `name`, `slug`, `description`, `image`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Web MMO', 'web-mmo', 'Các website chuyên về MMO, kiếm tiền online, tài chính', 'https://via.placeholder.com/400x300', 'active', 1, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(2, 'Website Bán Hàng', 'website-ban-hang', 'Website thương mại điện tử, bán hàng trực tuyến', 'https://via.placeholder.com/400x300', 'active', 2, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(3, 'Website Tin Tức', 'website-tin-tuc', 'Website tin tức, blog, tạp chí trực tuyến', 'https://via.placeholder.com/400x300', 'active', 3, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(4, 'Website Du Lịch', 'website-du-lich', 'Website du lịch, đặt tour, khách sạn', 'https://via.placeholder.com/400x300', 'active', 4, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(5, 'Website Giáo Dục', 'website-giao-duc', 'Website giáo dục, học trực tuyến, trung tâm', 'https://via.placeholder.com/400x300', 'active', 5, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(6, 'Website Y Tế', 'website-y-te', 'Website y tế, phòng khám, bệnh viện', 'https://via.placeholder.com/400x300', 'active', 6, '2025-11-01 08:41:49', '2025-11-01 08:41:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `website_interfaces`
--

CREATE TABLE `website_interfaces` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `hosting_price` int(255) NOT NULL,
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `website_interfaces`
--

INSERT INTO `website_interfaces` (`id`, `category_id`, `name`, `description`, `category`, `hosting_price`, `price`, `sale_price`, `discount_3_months`, `discount_6_months`, `discount_12_months`, `image`, `demo_url`, `features`, `status`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 'MÃ NGUỒN AI GENERATE V1', 'Mã nguồn website AI Generator phiên bản 1 với đầy đủ tính năng', 'WEB MMO', 200000, 500000.00, 200000.00, 5.00, 10.00, 20.00, 'https://dacy.vn/anh/51d79d8cfd2804abaffa1077d7e465d4.png', 'https://demo.example.com', 'AI Generator, Responsive, Admin Panel, Payment Integration', 'active', 1, '2025-11-01 08:41:49', '2025-11-01 16:12:49'),
(2, 2, 'WEBSITE BÁN HÀNG', 'Website bán hàng chuyên nghiệp với giỏ hàng và thanh toán', 'E-COMMERCE', 0, 800000.00, 400000.00, 3.00, 8.00, 15.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Shopping Cart, Payment, Inventory, Admin Dashboard', 'active', 1, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(3, 3, 'WEBSITE TIN TỨC', 'Website tin tức, blog với quản lý bài viết chuyên nghiệp', 'NEWS', 0, 600000.00, 300000.00, 5.00, 12.00, 25.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Article Management, Categories, Comments, SEO', 'active', 0, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(4, 4, 'WEBSITE DU LỊCH', 'Website du lịch, đặt tour với hệ thống quản lý tour', 'TRAVEL', 0, 1200000.00, 800000.00, 7.00, 15.00, 30.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Tour Management, Booking, Payment, Gallery', 'active', 0, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(5, 1, 'WEBSITE INVESTMENT', 'Website đầu tư, tài chính với nhiều tính năng chuyên nghiệp', 'WEB MMO', 0, 1500000.00, 1000000.00, 10.00, 20.00, 35.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Investment Plans, Payment Gateway, User Dashboard, Analytics', 'active', 1, '2025-11-01 08:41:49', '2025-11-01 08:41:49'),
(6, 5, 'WEBSITE GIÁO DỤC', 'Website giáo dục, học trực tuyến với quản lý khóa học', 'EDUCATION', 0, 900000.00, 600000.00, 4.00, 9.00, 18.00, 'https://via.placeholder.com/400x300', 'https://demo.example.com', 'Course Management, Video Lessons, Student Dashboard, Certificates', 'active', 0, '2025-11-01 08:41:49', '2025-11-01 08:41:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `website_orders`
--

CREATE TABLE `website_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `interface_id` int(11) NOT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `dot_id` int(11) DEFAULT NULL,
  `months` int(11) DEFAULT 1,
  `price` decimal(15,2) NOT NULL,
  `status` enum('pending','processing','active','expired','cancelled') DEFAULT 'pending',
  `order_date` timestamp NULL DEFAULT current_timestamp(),
  `expiry_date` timestamp NULL DEFAULT NULL,
  `domain_expiry_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `website_orders`
--

INSERT INTO `website_orders` (`id`, `user_id`, `interface_id`, `domain`, `dot_id`, `months`, `price`, `status`, `order_date`, `expiry_date`, `domain_expiry_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'vpsre.vn', 2, 1, 850000.00, 'active', '2025-11-01 08:48:30', '2025-11-01 08:48:30', '2026-11-01 08:48:30', '2025-11-01 08:48:30', '2025-11-01 16:45:28'),
(2, 1, 1, 'shopsieugame.biz', 6, 1, 480000.00, 'active', '2025-11-01 09:13:12', '2026-02-01 09:13:12', '2026-10-02 09:13:12', '2025-11-01 09:13:12', '2025-11-01 16:33:37');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_code` (`bank_code`);

--
-- Chỉ mục cho bảng `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`),
  ADD KEY `type` (`type`);

--
-- Chỉ mục cho bảng `dots`
--
ALTER TABLE `dots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `status` (`status`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- Chỉ mục cho bảng `renewal_history`
--
ALTER TABLE `renewal_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `transaction_logs`
--
ALTER TABLE `transaction_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type` (`type`),
  ADD KEY `status` (`status`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `remember_token` (`remember_token`);

--
-- Chỉ mục cho bảng `website_categories`
--
ALTER TABLE `website_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `status` (`status`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Chỉ mục cho bảng `website_interfaces`
--
ALTER TABLE `website_interfaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `category` (`category`),
  ADD KEY `status` (`status`),
  ADD KEY `is_featured` (`is_featured`);

--
-- Chỉ mục cho bảng `website_orders`
--
ALTER TABLE `website_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `interface_id` (`interface_id`),
  ADD KEY `dot_id` (`dot_id`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dots`
--
ALTER TABLE `dots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `renewal_history`
--
ALTER TABLE `renewal_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `website_categories`
--
ALTER TABLE `website_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `website_interfaces`
--
ALTER TABLE `website_interfaces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `website_orders`
--
ALTER TABLE `website_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
