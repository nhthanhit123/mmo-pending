<?php
if(isLogin()){
    echo redirect('/');
    exit();
}

$token = isset($_GET['token']) ? x($_GET['token']) : '';
$error = '';
$success = '';

if(empty($token)){
    $error = 'Link đặt lại mật khẩu không hợp lệ!';
} else {
    $checkToken = $nify->query("SELECT `email`, `expires_at` FROM `password_resets` WHERE `token` = '$token' AND `expires_at` > NOW()");
    if($checkToken->num_rows == 0){
        $error = 'Link đặt lại mật khẩu đã hết hạn hoặc không hợp lệ!';
    } else {
        $resetData = $checkToken->fetch_assoc();
        $email = $resetData['email'];
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $password = x($_POST['password']);
            $confirm_password = x($_POST['confirm_password']);
            
            if(empty($password) || empty($confirm_password)){
                $error = 'Vui lòng nhập đầy đủ thông tin!';
            } elseif(strlen($password) < 6){
                $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
            } elseif($password !== $confirm_password){
                $error = 'Mật khẩu xác nhận không khớp!';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $nify->query("UPDATE `users` SET `password` = '$hashedPassword' WHERE `email` = '$email'");
                
                if($nify->affected_rows > 0){
                    // Delete the token
                    $nify->query("DELETE FROM `password_resets` WHERE `token` = '$token'");
                    $success = 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.';
                    echo redirect('/auth/login?success=' . urlencode($success));
                    exit();
                } else {
                    $error = 'Có lỗi xảy ra! Vui lòng thử lại.';
                }
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
                        <h3 class="nk-block-title page-title">Đặt Lại Mật Khẩu</h3>
                        <div class="nk-block-des text-soft">
                            <p>Tạo mật khẩu mới cho tài khoản của bạn</p>
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
                                        <h5 class="nk-block-title">Tạo Mật Khẩu Mới</h5>
                                        <div class="nk-block-des">
                                            <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if($error): ?>
                                <div class="alert alert-danger alert-icon">
                                    <em class="icon ni ni-cross-circle"></em>
                                    <strong>Thất bại!</strong> <?= $error ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(!$error): ?>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class="form-label" for="password">Mật khẩu mới</label>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="confirm_password">Xác nhận mật khẩu mới</label>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="confirm_password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Đặt Lại Mật Khẩu</button>
                                    </div>
                                </form>
                                <?php endif; ?>
                                
                                <div class="form-note-s2 text-center pt-4">
                                    <p>Quay lại <a href="/auth/login">Đăng nhập</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passcodeSwitches = document.querySelectorAll('.passcode-switch');
    
    passcodeSwitches.forEach(function(switchElement) {
        switchElement.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            
            if(targetInput) {
                const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                targetInput.setAttribute('type', type);
                
                const iconShow = this.querySelector('.icon-show');
                const iconHide = this.querySelector('.icon-hide');
                
                if(type === 'text') {
                    iconShow.style.display = 'none';
                    iconHide.style.display = 'block';
                } else {
                    iconShow.style.display = 'block';
                    iconHide.style.display = 'none';
                }
            }
        });
    });
});
</script>