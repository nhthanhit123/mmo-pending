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

// Lấy ID đơn hàng
$orderId = (int)$_POST['order_id'];

// Validate
if(empty($orderId)) {
    echo json_encode(['status' => false, 'message' => 'ID đơn hàng không hợp lệ!']);
    exit;
}

// Kiểm tra đơn hàng có tồn tại và thuộc về user không
$order = $nify->query("SELECT * FROM `website_orders` WHERE `id` = $orderId AND `user_id` = ".$getUser['id'])->fetch_assoc();
if(!$order) {
    echo json_encode(['status' => false, 'message' => 'Đơn hàng không tồn tại!']);
    exit;
}

// Kiểm tra trạng thái đơn hàng có thể hủy không
if(!in_array($order['status'], ['pending', 'processing'])) {
    echo json_encode(['status' => false, 'message' => 'Đơn hàng này không thể hủy!']);
    exit;
}

// Bắt đầu transaction
$nify->begin_transaction();

try {
    // Cập nhật trạng thái đơn hàng
    $nify->query("UPDATE `website_orders` SET `status` = 'cancelled' WHERE `id` = $orderId");
    
    // Hoàn tiền cho user
    $nify->query("UPDATE `users` SET `balance` = `balance` + ".$order['price']." WHERE `id` = ".$getUser['id']);
    
    // Ghi log giao dịch
    $nify->query("INSERT INTO `transaction_logs` (`user_id`, `amount`, `type`, `description`, `created_at`) 
                 VALUES (".$getUser['id'].", ".$order['price'].", 'refund', 'Hoàn tiền đơn hàng #".str_pad($orderId, 6, '0', STR_PAD_LEFT)." - Website: {$order['domain']}', NOW())");
    
    $nify->commit();
    
    echo json_encode([
        'status' => true, 
        'message' => 'Đã hủy đơn hàng và hoàn tiền thành công!'
    ]);
    
} catch (Exception $e) {
    $nify->rollback();
    echo json_encode(['status' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại!']);
}
?>