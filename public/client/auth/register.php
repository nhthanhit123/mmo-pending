<?php
$uri_notLogin = $_GET['uri'] ?? '/';
$_SESSION['uri_login'] = $uri_notLogin;
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content">
            <div class="nk-block nk-block-middle nk-auth-body wide-xs">
                <div class="brand-logo pb-4 text-center">
                    <a href="/" class="logo-link">
                        <img class="logo-light logo-img logo-img-lg" src="/images/logo.png" alt="logo">
                        <img class="logo-dark logo-img logo-img-lg" src="/images/logo-dark.png" alt="logo-dark">
                    </a>
                </div>

                <div class="card">
                    <div class="card-inner card-inner-lg">
                        <div class="nk-block-head text-center mb-3">
                            <div class="nk-block-head-content">
                                <h4 class="nk-block-title">Đăng ký tài khoản</h4>
                                <div class="nk-block-des">
                                    <p>Tạo tài khoản mới để bắt đầu trải nghiệm tuyệt vời</p>
                                </div>
                            </div>
                        </div>

                        <div id="alertBox"></div>

                        <form id="registerForm" autocomplete="off" novalidate>
                            <?php csrf_field(); ?>
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="username">Tên đăng nhập</label>
                                </div>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="email">Địa chỉ Email</label>
                                </div>
                                <div class="form-control-wrap">
                                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Nhập email của bạn" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="phone">Số điện thoại (tùy chọn)</label>
                                </div>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control form-control-lg" id="phone" name="phone" placeholder="Nhập số điện thoại">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="password">Mật khẩu</label>
                                </div>
                                <div class="form-control-wrap">
                                    <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                    </a>
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Nhập mật khẩu" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="confirm_password">Xác nhận mật khẩu</label>
                                </div>
                                <div class="form-control-wrap">
                                    <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="confirm_password">
                                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                    </a>
                                    <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-lg btn-primary btn-block" type="submit" id="btnRegister">
                                    <span class="btn-text">Đăng ký</span>
                                    <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner" role="status"></span>
                                </button>
                            </div>
                        </form>

                        <div class="form-note-s2 text-center pt-4">
                            <p>Đã có tài khoản? <a href="/auth/login">Đăng nhập ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const alertBox = document.getElementById('alertBox');
    const btnRegister = document.getElementById('btnRegister');
    const spinner = document.getElementById('loadingSpinner');

    // Submit AJAX
    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        alertBox.innerHTML = '';
        btnRegister.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(registerForm);

        try {
            const response = await fetch('/ajaxs/client/register', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status) {
                alertBox.innerHTML = `
                    <div class="alert alert-success alert-icon text-center">
                        <em class="icon ni ni-check-circle"></em> ${result.message}
                    </div>`;
                setTimeout(() => {
                    window.location.href = result.redirect || '/auth/login';
                }, 1500);
            } else {
                alertBox.innerHTML = `
                    <div class="alert alert-danger alert-icon text-center">
                        <em class="icon ni ni-cross-circle"></em> ${result.message}
                    </div>`;
            }
        } catch (error) {
            alertBox.innerHTML = `
                <div class="alert alert-warning alert-icon text-center">
                    <em class="icon ni ni-alert-circle"></em> Lỗi kết nối máy chủ!
                </div>`;
        } finally {
            btnRegister.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    // 👁 Toggle password
    document.querySelectorAll('.passcode-switch').forEach(sw => {
        sw.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const iconShow = this.querySelector('.icon-show');
            const iconHide = this.querySelector('.icon-hide');

            if (input.type === 'password') {
                input.type = 'text';
                iconShow.style.display = 'none';
                iconHide.style.display = 'block';
            } else {
                input.type = 'password';
                iconShow.style.display = 'block';
                iconHide.style.display = 'none';
            }
        });
    });
});
</script>
