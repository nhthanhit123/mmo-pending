<?php
header('Content-Type: application/json; charset=utf-8');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = x($_POST['username']);
    $password = x($_POST['password']);
    $remember = isset($_POST['remember']) ? 1 : 0;

    if(empty($username) || empty($password)){
        echo json_encode([
            'status' => false,
            'message' => 'Vui lòng nhập đầy đủ thông tin!'
        ]);
        exit;
    }

    $checkUser = $nify->query("
        SELECT * FROM `users` 
        WHERE (`username` = '$username' OR `email` = '$username') 
        AND `status` = 'active'
    ");

    if($checkUser->num_rows == 0){
        echo json_encode([
            'status' => false,
            'message' => 'Tài khoản không tồn tại hoặc đã bị khóa!'
        ]);
        exit;
    }

    $user = $checkUser->fetch_assoc();

    if(!password_verify($password, $user['password'])){
        echo json_encode([
            'status' => false,
            'message' => 'Mật khẩu không chính xác!'
        ]);
        exit;
    }

    $token = generateSecureToken(32);
    $expires = $remember ? time() + (30 * 24 * 60 * 60) : time() + (24 * 60 * 60);

    setcookie('remember_token', $token, $expires, '/', '', false, true);
    $nify->query("UPDATE `users` SET `remember_token` = '".md5($token)."', `ip` = '".$_SERVER['REMOTE_ADDR']."' WHERE `id` = '".$user['id']."'");

    echo json_encode([
        'status' => true,
        'message' => 'Đăng nhập thành công!',
        'redirect' => $_SESSION['uri_login'] ?? '/'
    ]);
    exit;
}

echo json_encode([
    'status' => false,
    'message' => 'Phương thức không hợp lệ'
]);
exit;
?>
