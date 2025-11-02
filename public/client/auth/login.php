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
                                <h4 class="nk-block-title">Đăng nhập</h4>
                                <div class="nk-block-des">
                                    <p>Truy cập vào hệ thống bằng tài khoản của bạn.</p>
                                </div>
                            </div>
                        </div>

                        <div id="alertBox"></div>

                        <form id="loginForm" autocomplete="off" novalidate>
                            <?php csrf_field();
                            if(!isset($_SESSION['csrf_token'])){
                                redirect('');
                            }
                            ?>
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="username">Email hoặc Tên đăng nhập</label>
                                </div>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Nhập email hoặc tên đăng nhập" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label-group d-flex justify-content-between align-items-center">
                                    <label class="form-label" for="password">Mật khẩu</label>
                                    <a class="link link-primary link-sm" href="/auth/forgot">Quên mật khẩu?</a>
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
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                    <label class="custom-control-label" for="remember">Ghi nhớ đăng nhập</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-lg btn-primary btn-block" type="submit" id="btnLogin">
                                    <span class="btn-text">Đăng nhập</span>
                                    <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner" role="status"></span>
                                </button>
                            </div>
                        </form>

                        <div class="form-note-s2 text-center pt-4">
                            <p>Chưa có tài khoản? <a href="/auth/register">Tạo tài khoản mới</a></p>
                        </div>

                        <div class="text-center pt-4 pb-3">
                            <h6 class="overline-title overline-title-sap"><span>HOẶC</span></h6>
                        </div>

                        <ul class="nav justify-center gx-4">
                            <li class="nav-item"><a class="link link-primary fw-normal py-2 px-3" href="#">Facebook</a></li>
                            <li class="nav-item"><a class="link link-primary fw-normal py-2 px-3" href="#">Google</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const alertBox = document.getElementById('alertBox');
    const btnLogin = document.getElementById('btnLogin');
    const spinner = document.getElementById('loadingSpinner');

    // Submit AJAX
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        alertBox.innerHTML = '';
        btnLogin.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(loginForm);

        try {
            const response = await fetch('/ajaxs/client/login', {
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
                    window.location.href = result.redirect || '/';
                }, 1000);
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
            btnLogin.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    <?php
    
    if(isLogin()){
    ?>
    // Đăng Xuất
    fetch('/ajaxs/logout')
      .then(res => res.json())
      .then(data => console.log(data));
      console.log('Chạy đi');
      
      <?php } ?>

    // 👁 Toggle password
    const passSwitch = document.querySelector('.passcode-switch');
    const passwordInput = document.getElementById('password');

    if (passSwitch && passwordInput) {
        passSwitch.addEventListener('click', function(e) {
            e.preventDefault();
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            const iconShow = this.querySelector('.icon-show');
            const iconHide = this.querySelector('.icon-hide');

            if (type === 'text') {
                iconShow.style.display = 'none';
                iconHide.style.display = 'block';
            } else {
                iconShow.style.display = 'block';
                iconHide.style.display = 'none';
            }
        });
    }
});
</script>
