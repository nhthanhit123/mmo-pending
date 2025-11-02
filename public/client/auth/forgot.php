<?php
if(isLogin()){
    echo redirect('/');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = x($_POST['email']);
    
    if(empty($email)){
        $error = 'Vui lòng nhập email!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = 'Email không hợp lệ!';
    } else {
        $checkUser = $nify->query("SELECT `id`, `username` FROM `users` WHERE `email` = '$email' AND `status` = 'active'");
        if($checkUser->num_rows == 0){
            $error = 'Email không tồn tại trong hệ thống!';
        } else {
            $user = $checkUser->fetch_assoc();
            $token = md5($user['id'] . time() . rand(1000, 9999));
            $expires = time() + (60 * 60); // 1 hour
            
            // Delete old tokens
            $nify->query("DELETE FROM `password_resets` WHERE `email` = '$email'");
            
            // Insert new token
            $nify->query("INSERT INTO `password_resets` (`email`, `token`, `expires_at`) VALUES ('$email', '$token', FROM_UNIXTIME($expires))");
            
            if($nify->affected_rows > 0){
                $resetLink = "https://$_SERVER[HTTP_HOST]/auth/reset?token=$token";
                
                // In real implementation, send email here
                // For demo, we'll show success message
                $success = 'Chúng tôi đã gửi link đặt lại mật khẩu vào email của bạn. Vui lòng kiểm tra hòm thư (bao gồm cả spam).';
                
                // Debug: show the reset link (remove in production)
                $debug_link = $resetLink;
            } else {
                $error = 'Có lỗi xảy ra! Vui lòng thử lại.';
            }
        }
    }
}
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Quên Mật Khẩu</h3>
                        <div class="nk-block-des text-soft">
                            <p>Nhập email để nhận link đặt lại mật khẩu</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="nk-block">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="card card-bordered">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">Đặt Lại Mật Khẩu</h5>
                                        <div class="nk-block-des">
                                            <p>Nhập email đã đăng ký để nhận hướng dẫn đặt lại mật khẩu</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if(isset($error)): ?>
                                <div class="alert alert-danger alert-icon">
                                    <em class="icon ni ni-cross-circle"></em>
                                    <strong>Thất bại!</strong> <?= $error ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(isset($success)): ?>
                                <div class="alert alert-success alert-icon">
                                    <em class="icon ni ni-check-circle"></em>
                                    <strong>Thành công!</strong> <?= $success ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(isset($debug_link)): ?>
                                <div class="alert alert-info alert-icon">
                                    <em class="icon ni ni-info"></em>
                                    <strong>Debug (chỉ hiển thị trong demo):</strong><br>
                                    <a href="<?= $debug_link ?>"><?= $debug_link ?></a>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(!isset($success)): ?>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email đăng ký</label>
                                        <div class="form-control-wrap">
                                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Nhập email đã đăng ký" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Gửi Link Đặt Lại</button>
                                    </div>
                                </form>
                                <?php endif; ?>
                                
                                <div class="form-note-s2 text-center pt-4">
                                    <p>Quay lại <a href="/auth/login">Đăng nhập</a></p>
                                    <p>Chưa có tài khoản? <a href="/auth/register">Đăng ký ngay</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>