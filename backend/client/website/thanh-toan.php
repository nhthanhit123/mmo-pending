<?php
header('Content-Type: application/json; charset=utf-8');

// Kiểm tra CSRF
if(!csrf_verify()) {
    echo json_encode(['status' => false, 'message' => 'CSRF token không hợp lệ!']);
    exit;
}

// Kiểm tra user đã đăng nhập chưa
if(!isLogin()) {
    echo json_encode(['status' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit;
}

// Lấy dữ liệu từ POST
$interfaceId = (int)$_POST['interface_id'];
$domain = x($_POST['domain']);
$dotId = (int)$_POST['dot_id'];
$months = (int)$_POST['months'];

// Validate
if(empty($interfaceId) || empty($domain) || empty($dotId) || empty($months)) {
    echo json_encode(['status' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
    exit;
}

// Validate months
if(!in_array($months, [1, 3, 6, 12, 24])) {
    echo json_encode(['status' => false, 'message' => 'Số tháng không hợp lệ!']);
    exit;
}

// Kiểm tra giao diện
$interface = $nify->query("SELECT * FROM `website_interfaces` WHERE `id` = $interfaceId AND `status` = 'active'")->fetch_assoc();
if(!$interface) {
    echo json_encode(['status' => false, 'message' => 'Giao diện không tồn tại!']);
    exit;
}

// Kiểm tra đuôi miền
$dot = $nify->query("SELECT * FROM `dots` WHERE `id` = $dotId AND `status` = 'active'")->fetch_assoc();
if(!$dot) {
    echo json_encode(['status' => false, 'message' => 'Đuôi miền không hợp lệ!']);
    exit;
}

// Kiểm tra tên miền đã tồn tại chưa
$fullDomain = $domain . $dot['name'];
$checkDomain = $nify->query("SELECT * FROM `website_orders` WHERE `domain` = '$fullDomain' AND `status` != 'cancelled'")->num_rows;
if($checkDomain > 0) {
    echo json_encode(['status' => false, 'message' => 'Tên miền này đã được đăng ký!']);
    exit;
}

// Tính giá với chiết khấu theo tháng
$interfacePrice = $interface['sale_price'] ?: $interface['price'];
$discountRates = [
    1 => 0,
    3 => $interface['discount_3_months'] / 100,
    6 => $interface['discount_6_months'] / 100,
    12 => $interface['discount_12_months'] / 100,
    24 => $interface['discount_12_months'] / 100 // 24 tháng cùng chiết khấu với 12 tháng
];
$discountRate = $discountRates[$months] ?? 0;
$discountAmount = $interfacePrice * $discountRate;
$discountedInterfacePrice = $interfacePrice - $discountAmount;

// Tính tổng giá
$totalInterfacePrice = $discountedInterfacePrice * $months;

// Domain price calculation
$totalDomainPrice = $dot['price']; // Default 1 year
if ($months >= 24) {
    $totalDomainPrice = $dot['price'] * 2; // 2 years for 24+ months
}

$totalPrice = $totalInterfacePrice + $totalDomainPrice;

// Kiểm tra số dư
if($getUser['balance'] < $totalPrice) {
    echo json_encode(['status' => false, 'message' => 'Số dư không đủ. Vui lòng nạp thêm tiền!']);
    exit;
}

// Bắt đầu transaction
$nify->begin_transaction();

try {
    // Trừ tiền user
    $nify->query("UPDATE `users` SET `balance` = `balance` - $totalPrice WHERE `id` = ".$getUser['id']);
    
    // Tính ngày hết hạn
    $expiryDate = date('Y-m-d H:i:s', strtotime("+$months months"));
    // Domain expiry date
    $domainExpiryDate = date('Y-m-d H:i:s', strtotime('+12 months'));
    if ($months >= 24) {
        $domainExpiryDate = date('Y-m-d H:i:s', strtotime('+24 months')); // 2 years for 24+ months
    }
    
    // Tạo đơn hàng
    $nify->query("INSERT INTO `website_orders` (`user_id`, `interface_id`, `domain`, `dot_id`, `months`, `price`, `status`, `expiry_date`, `domain_expiry_date`) 
                 VALUES (".$getUser['id'].", $interfaceId, '$fullDomain', $dotId, $months, $totalPrice, 'pending', '$expiryDate', '$domainExpiryDate')");
    
    $orderId = $nify->insert_id;
    
    // Ghi log giao dịch
    $discountText = $discountAmount > 0 ? " (Chiết khấu: ".number_format($discountAmount, 0, ',', '.')."đ)" : "";
    $nify->query("INSERT INTO `transaction_logs` (`user_id`, `amount`, `type`, `description`, `created_at`) 
                 VALUES (".$getUser['id'].", $totalPrice, 'website_order', 'Thuê website $fullDomain - $months tháng - Giao diện: {$interface['name']}$discountText', NOW())");
    
    $nify->commit();
    
    echo json_encode([
        'status' => true, 
        'message' => 'Đặt hàng thành công! Vui lòng chờ xử lý.',
        'order_id' => $orderId,
        'redirect' => '/manage/web'
    ]);
    
} catch (Exception $e) {
    $nify->rollback();
    echo json_encode(['status' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại!']);
}
?>