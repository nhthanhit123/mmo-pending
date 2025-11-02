<?php
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isLogin()) {
    echo json_encode(['status' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit;
}

// Lấy dữ liệu từ POST
$orderId     = (int)($_POST['order_id'] ?? 0);
$months      = (int)($_POST['months'] ?? 0);
$renewDomain = isset($_POST['renew_domain']) ? 1 : 0;

// Validate
if (empty($orderId) || empty($months)) {
    echo json_encode(['status' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
    exit;
}
if (!in_array($months, [1,3,6,12,24], true)) {
    echo json_encode(['status' => false, 'message' => 'Số tháng không hợp lệ!']);
    exit;
}

// Kiểm tra đơn hàng có tồn tại và thuộc về user không
$sql = "
    SELECT 
        wo.*, 
        wi.name AS interface_name, 
        wi.image AS interface_image, 
        wi.description AS interface_description, 
        wi.features AS interface_features, 
        wi.hosting_price AS interface_hosting_price,
        d.name AS dot_name, 
        d.price AS dot_price, 
        d.renewal_price AS dot_renewal_price
    FROM `website_orders` wo
    LEFT JOIN `website_interfaces` wi ON wo.interface_id = wi.id
    LEFT JOIN `dots` d ON wo.dot_id = d.id
    WHERE wo.id = $orderId AND wo.user_id = ".$getUser['id'];

$order = $nify->query($sql)->fetch_assoc();

if (!$order) {
    echo json_encode(['status' => false, 'message' => 'Đơn hàng không tồn tại hoặc không thể gia hạn!']);
    exit;
}

// Logic tự động kiểm tra và quyết định gia hạn tên miền
$shouldRenewDomain = false;
$domainYears = 1;

// Nếu có ngày hết hạn tên miền thì tính; nếu không có => coi như đã hết hạn
try {
    $domainExpiryDate = !empty($order['domain_expiry_date'])
        ? new DateTime($order['domain_expiry_date'])
        : null;
} catch (Exception $e) {
    $domainExpiryDate = null;
}

// PHP không có class Date -> dùng DateTime
$currentDate = new DateTime();

if ($domainExpiryDate instanceof DateTime) {
    // xấp xỉ số tháng còn lại theo ngày
    $daysDiff = (int)floor(($domainExpiryDate->getTimestamp() - $currentDate->getTimestamp()) / (60*60*24));
    $monthsUntilDomainExpiry = (int)floor($daysDiff / 30);
    if ($monthsUntilDomainExpiry <= 0) { 
        $shouldRenewDomain = true;
    }
} else {
    // Không có ngày hết hạn -> xem như đã hết hạn
    $shouldRenewDomain = true;
    $monthsUntilDomainExpiry = 0;
}

// Giả sử giá hosting là 200,000đ/tháng (có thể lấy từ database sau)
$hostingPricePerMonth = (int)$order['interface_hosting_price'];
$hostingAmount = $hostingPricePerMonth * $months;

// Giá tên miền
$domainAmount = 0;
if ($shouldRenewDomain) {
    $domainAmount = ((int)$order['dot_renewal_price']) * $domainYears;
}

// Tổng giá
$totalAmount = $hostingAmount + $domainAmount;

// Kiểm tra số dư
if ($getUser['balance'] < $totalAmount) {
    echo json_encode(['status' => false, 'message' => 'Số dư không đủ. Vui lòng nạp thêm tiền!']);
    exit;
}

// Bắt đầu transaction
$nify->begin_transaction();

try {
    // Trừ tiền user
    $nify->query("UPDATE `users` SET `balance` = `balance` - $totalAmount WHERE `id` = ".$getUser['id']);

    // Lấy ngày hết hạn cũ
    $oldExpiryDate        = $order['expiry_date'];
    $oldDomainExpiryDate  = $order['domain_expiry_date'];

    // Tính ngày hết hạn mới
    $newExpiryDate = new DateTime($oldExpiryDate);
    $newExpiryDate->add(new DateInterval('P'.$months.'M'));
    $newExpiryDateStr = $newExpiryDate->format('Y-m-d H:i:s');

    // Nếu gia hạn tên miền
    $newDomainExpiryDateStr = $oldDomainExpiryDate;
    if ($shouldRenewDomain) {
        // Nếu trống thì bắt đầu từ hiện tại
        $baseDomainExpiry = !empty($oldDomainExpiryDate) ? new DateTime($oldDomainExpiryDate) : new DateTime();
        $baseDomainExpiry->add(new DateInterval('P'.$domainYears.'Y'));
        $newDomainExpiryDateStr = $baseDomainExpiry->format('Y-m-d H:i:s');
    }

    // Cập nhật đơn hàng
    $nify->query(
        "UPDATE `website_orders`
         SET `expiry_date` = '$newExpiryDateStr', `domain_expiry_date` = '$newDomainExpiryDateStr'
         WHERE `id` = $orderId"
    );

    // Ghi lịch sử gia hạn
    $nify->query(
        "INSERT INTO `renewal_history`
         (`order_id`, `user_id`, `months`, `amount`, `renew_domain`, `domain_amount`, `hosting_amount`,
          `old_expiry_date`, `new_expiry_date`, `old_domain_expiry_date`, `new_domain_expiry_date`)
         VALUES
         ($orderId, ".$getUser['id'].", $months, $totalAmount, ".(int)$shouldRenewDomain.", $domainAmount, $hostingAmount,
          '$oldExpiryDate', '$newExpiryDateStr', ".($oldDomainExpiryDate ? "'$oldDomainExpiryDate'" : "NULL").",
          ".($newDomainExpiryDateStr ? "'$newDomainExpiryDateStr'" : "NULL").")"
    );

    // Ghi log giao dịch
    $renewText = "Gia hạn hosting $months tháng";
    if ($shouldRenewDomain) {
        $renewText .= " và tên miền ($domainYears năm)";
    }
    $renewText .= " cho website {$order['domain']}";
    if (isset($monthsUntilDomainExpiry)) {
        $renewText .= " [Tự động gia hạn tên miền - Còn $monthsUntilDomainExpiry tháng]";
    }

    // Escape đơn giản cho chuỗi (nếu cần)
    $renewTextEsc = $nify->real_escape_string($renewText);

    $nify->query(
        "INSERT INTO `transaction_logs` (`user_id`, `amount`, `type`, `description`, `created_at`)
         VALUES (".$getUser['id'].", $totalAmount, 'renewal', '$renewTextEsc', NOW())"
    );

    $nify->commit();

    echo json_encode([
        'status' => true,
        'message' => 'Gia hạn dịch vụ thành công!',
        'new_expiry_date' => $newExpiryDateStr,
        'new_domain_expiry_date' => $newDomainExpiryDateStr
    ]);

} catch (Exception $e) {
    $nify->rollback();
    echo json_encode(['status' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại!']);
}
