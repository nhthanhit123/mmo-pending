<?php
if(isset($_POST['card_deposit'])){
    $card_type = x($_POST['card_type']);
    $card_code = x($_POST['card_code']);
    $card_serial = x($_POST['card_serial']);
    $amount = x($_POST['amount']);
    
    if(empty($card_type) || empty($card_code) || empty($card_serial) || empty($amount)){
        $error = 'Vui lòng nhập đầy đủ thông tin thẻ cào!';
    } elseif(!in_array($card_type, ['VIETTEL', 'MOBIFONE', 'VINAPHONE', 'VIETNAMOBILE', 'ZING'])){
        $error = 'Loại thẻ không hợp lệ!';
    } elseif(!is_numeric($amount) || $amount < 10000 || $amount > 500000){
        $error = 'Mệnh giá thẻ không hợp lệ (10.000đ - 500.000đ)!';
    } else {
        $transaction_id = 'CARD_' . time() . '_' . rand(1000, 9999);
        $nify->query("INSERT INTO `deposits` (`user_id`, `amount`, `type`, `status`, `card_type`, `card_code`, `card_serial`, `transaction_id`) VALUES ('".$getUser['id']."', '$amount', 'card', 'pending', '$card_type', '$card_code', '$card_serial', '$transaction_id')");
        
        if($nify->affected_rows > 0){
            $success = 'Gửi yêu cầu nạp thẻ thành công! Vui lòng chờ xử lý.';
        } else {
            $error = 'Gửi yêu cầu thất bại! Vui lòng thử lại.';
        }
    }
}
?>