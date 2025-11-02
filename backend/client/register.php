<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = x($_POST['username']);
    $email = x($_POST['email']);
    $password = x($_POST['password']);
    $confirm_password = x($_POST['confirm_password']);
    $phone = x($_POST['phone']);

    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)){
        echo json_encode(['status' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
        exit;
    }

    if(strlen($username) < 3 || strlen($username) > 50){
        echo json_encode(['status' => false, 'message' => 'Tên đăng nhập phải từ 3–50 ký tự!']);
        exit;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo json_encode(['status' => false, 'message' => 'Email không hợp lệ!']);
        exit;
    }

    if(strlen($password) < 6){
        echo json_encode(['status' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự!']);
        exit;
    }

    if($password !== $confirm_password){
        echo json_encode(['status' => false, 'message' => 'Mật khẩu xác nhận không khớp!']);
        exit;
    }

    if(!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)){
        echo json_encode(['status' => false, 'message' => 'Số điện thoại không hợp lệ!']);
        exit;
    }

    $checkUsername = $nify->query("SELECT `id` FROM `users` WHERE `username` = '$username'")->num_rows;
    $checkEmail = $nify->query("SELECT `id` FROM `users` WHERE `email` = '$email'")->num_rows;

    if($checkUsername > 0){
        echo json_encode(['status' => false, 'message' => 'Tên đăng nhập đã tồn tại!']);
        exit;
    }

    if($checkEmail > 0){
        echo json_encode(['status' => false, 'message' => 'Email đã được sử dụng!']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $token = generateSecureToken(32);
    $expires = time() + (24 * 60 * 60);

    setcookie('remember_token', $token, $expires, '/', '', false, true);

    $insert = $nify->query("
        INSERT INTO `users` (`username`, `email`, `password`, `phone`, `ip`, `remember_token`, `created_at`) 
        VALUES ('$username', '$email', '$hashedPassword', '$phone', '".$_SERVER['REMOTE_ADDR']."', '".md5($token)."', '".time()."')
    ");

    if($nify->affected_rows > 0){
        echo json_encode([
            'status' => true,
            'message' => 'Đăng ký thành công! Đang chuyển hướng vui lòng chờ.',
            'redirect' => $_SESSION['uri_login'] ?? '/'
        ]);
        exit;
    } else {
        echo json_encode(['status' => false, 'message' => 'Đăng ký thất bại! Vui lòng thử lại.']);
        exit;
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}
?>
