<?php
if(!isLogin()){
    echo redirect('/auth/login');
    exit();
}

$success = '';
$error = '';

// Handle bank deposit
if(isset($_POST['bank_deposit'])){
    $bank_id = x($_POST['bank_id']);
    $amount = x($_POST['amount']);
    $transaction_id = x($_POST['transaction_id']);
    
    if(empty($bank_id) || empty($amount) || empty($transaction_id)){
        $error = 'Vui lòng nhập đầy đủ thông tin chuyển khoản!';
    } elseif(!is_numeric($amount) || $amount < 10000){
        $error = 'Số tiền không hợp lệ (tối thiểu 10.000đ)!';
    } else {
        $checkBank = $nify->query("SELECT * FROM `banks` WHERE `id` = '$bank_id' AND `status` = 'active'");
        if($checkBank->num_rows == 0){
            $error = 'Ngân hàng không hợp lệ!';
        } else {
            $nify->query("INSERT INTO `deposits` (`user_id`, `amount`, `type`, `status`, `bank_id`, `transaction_id`) VALUES ('".$getUser['id']."', '$amount', 'bank', 'pending', '$bank_id', '$transaction_id')");
            
            if($nify->affected_rows > 0){
                $success = 'Gửi yêu cầu nạp tiền thành công! Vui lòng chờ xác nhận.';
            } else {
                $error = 'Gửi yêu cầu thất bại! Vui lòng thử lại.';
            }
        }
    }
}

// Get banks
$banks = $nify->query("SELECT * FROM `banks` WHERE `status` = 'active' ORDER BY `bank_name`");

// Get deposit history
$deposits = $nify->query("SELECT d.*, b.bank_name FROM `deposits` d LEFT JOIN `banks` b ON d.bank_id = b.id WHERE d.user_id = '".$getUser['id']."' ORDER BY d.created_at DESC LIMIT 10");
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Nạp Tiền</h3>
                        <div class="nk-block-des text-soft">
                            <p>Nạp tiền vào tài khoản của bạn</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <div class="form-group">
                                            <label class="form-label">Số dư hiện tại:</label>
                                            <h4 class="amount"><?= number_format($getUser['balance'], 0, ',', '.') ?>đ</h4>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if($success): ?>
            <div class="alert alert-success alert-icon">
                <em class="icon ni ni-check-circle"></em>
                <strong>Thành công!</strong> <?= $success ?>
            </div>
            <?php endif; ?>
            
            <?php if($error): ?>
            <div class="alert alert-danger alert-icon">
                <em class="icon ni ni-cross-circle"></em>
                <strong>Thất bại!</strong> <?= $error ?>
            </div>
            <?php endif; ?>
            
            <div class="nk-block">
                <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-card">
                            <em class="icon ni ni-credit-card"></em>
                            <span>Thẻ Cào</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-bank">
                            <em class="icon ni ni-bank"></em>
                            <span>Ngân Hàng</span>
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- Tab Thẻ Cào -->
                    <div class="tab-pane active" id="tab-card">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title">Nạp Tiền Qua Thẻ Cào</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-inner">
                                <div id="alertBoxDeposit"></div>
                                <form id="depositForm" autocomplete="off">
                                    <?php csrf_field(); ?>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Loại thẻ</label>
                                                <select class="form-select" name="card_type" required>
                                                    <option value="">-- Chọn loại thẻ --</option>
                                                    <option value="Viettel">Viettel</option>
                                                    <option value="Mobifone">Mobifone</option>
                                                    <option value="Vinaphone">Vinaphone</option>
                                                    <option value="Vietnamobile">Vietnamobile</option>
                                                    <option value="Zing">Zing</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Mệnh giá (VNĐ)</label>
                                                <select class="form-select" name="amount" required>
                                                    <option value="">-- Chọn mệnh giá --</option>
                                                    <option value="10000">10.000</option>
                                                    <option value="20000">20.000</option>
                                                    <option value="30000">30.000</option>
                                                    <option value="50000">50.000</option>
                                                    <option value="100000">100.000</option>
                                                    <option value="200000">200.000</option>
                                                    <option value="300000">300.000</option>
                                                    <option value="500000">500.000</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Mã thẻ</label>
                                                <input type="text" class="form-control" name="card_code" placeholder="Nhập mã thẻ cào" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Số serial</label>
                                                <input type="text" class="form-control" name="card_serial" placeholder="Nhập số serial" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <em class="icon ni ni-info"></em>
                                                <strong>Lưu ý:</strong> Chiết khấu thẻ cào là 20%. Thẻ sẽ được xử lý trong vòng 5-15 phút.
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary" id="btnDeposit">
                                                <span class="btn-text">Nạp Thẻ</span>
                                                <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner" role="status"></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const depositForm = document.getElementById('depositForm');
                        const alertBox = document.getElementById('alertBoxDeposit');
                        const btn = document.getElementById('btnDeposit');
                        const spinner = document.getElementById('loadingSpinner');
                    
                        depositForm.addEventListener('submit', async function(e) {
                            e.preventDefault();
                            alertBox.innerHTML = '';
                            btn.disabled = true;
                            spinner.classList.remove('d-none');
                    
                            const formData = new FormData(depositForm);
                    
                            try {
                                const response = await fetch('/ajaxs/client/deposit', {
                                    method: 'POST',
                                    body: formData
                                });
                                const result = await response.json();
                    
                                if (result.status) {
                                    alertBox.innerHTML = `
                                        <div class="alert alert-success alert-icon text-center">
                                            <em class="icon ni ni-check-circle"></em> ${result.message}
                                        </div>`;
                                    depositForm.reset();
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
                                btn.disabled = false;
                                spinner.classList.add('d-none');
                            }
                        });
                    });
                    </script>

                    
                    <!-- Tab Ngân Hàng -->
                    <div class="tab-pane" id="tab-bank">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title">Nạp Tiền Qua Ngân Hàng</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-inner">
                                <div class="row g-4">
                                    <?php while($bank = $banks->fetch_assoc()): ?>
                                    <div class="col-md-6">
                                        <div class="card card-bordered border-light">
                                            <div class="card-inner">
                                                <div class="form-group">
                                                    <div class="custom-control custom-control-sm custom-radio">
                                                        <input type="radio" class="custom-control-input" id="bank_<?= $bank['id'] ?>" name="bank_id" value="<?= $bank['id'] ?>" required>
                                                        <label class="custom-control-label" for="bank_<?= $bank['id'] ?>">
                                                            <strong><?= $bank['bank_name'] ?></strong>
                                                        </label>
                                                    </div>
                                                    <div class="mt-2">
                                                        <p class="text-muted mb-1">
                                                            <strong>Chủ tài khoản:</strong> <?= $bank['account_name'] ?>
                                                        </p>
                                                        <p class="text-muted mb-1">
                                                            <strong>Số tài khoản:</strong> <?= $bank['account_number'] ?>
                                                        </p>
                                                        <p class="text-muted mb-0">
                                                            <strong>Chi nhánh:</strong> <?= $bank['branch'] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            <div class="card-inner">
                                <form action="" method="POST">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Số tiền chuyển khoản (VNĐ)</label>
                                                <input type="number" class="form-control" name="amount" placeholder="Nhập số tiền" min="10000" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Mã giao dịch</label>
                                                <input type="text" class="form-control" name="transaction_id" placeholder="Nhập mã giao dịch chuyển khoản" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <em class="icon ni ni-info"></em>
                                                <strong>Hướng dẫn:</strong> Chuyển khoản vào một trong các tài khoản trên, sau đó điền thông tin và mã giao dịch để được cộng tiền.
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="bank_deposit" class="btn btn-primary">Gửi Yêu Cầu</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lịch sử nạp tiền -->
            <div class="nk-block">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Lịch Sử Nạp Tiền</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-inner p-0">
                        <div class="nk-tb-list nk-tb-flush">
                            <div class="nk-tb-item nk-tb-head">
                                <div class="nk-tb-col"><span>Mã GD</span></div>
                                <div class="nk-tb-col"><span>Loại</span></div>
                                <div class="nk-tb-col"><span>Số tiền</span></div>
                                <div class="nk-tb-col"><span>Trạng thái</span></div>
                                <div class="nk-tb-col"><span>Thời gian</span></div>
                            </div>
                            <?php if($deposits->num_rows > 0): ?>
                                <?php while($deposit = $deposits->fetch_assoc()): ?>
                                <div class="nk-tb-item">
                                    <div class="nk-tb-col">
                                        <span class="tb-lead"><?= $deposit['transaction_id'] ?></span>
                                    </div>
                                    <div class="nk-tb-col">
                                        <?php if($deposit['type'] == 'card'): ?>
                                            <span class="badge bg-info">Thẻ cào</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Ngân hàng</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="nk-tb-col">
                                        <span class="tb-lead"><?= number_format($deposit['amount'], 0, ',', '.') ?>đ</span>
                                    </div>
                                    <div class="nk-tb-col">
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch($deposit['status']){
                                            case 'pending':
                                                $statusClass = 'bg-warning';
                                                $statusText = 'Đang xử lý';
                                                break;
                                            case 'success':
                                                $statusClass = 'bg-success';
                                                $statusText = 'Thành công';
                                                break;
                                            case 'failed':
                                                $statusClass = 'bg-danger';
                                                $statusText = 'Thất bại';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'bg-dark';
                                                $statusText = 'Đã hủy';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                    </div>
                                    <div class="nk-tb-col">
                                        <span class="tb-sub"><?= date('H:i d/m/Y', strtotime($deposit['created_at'])) ?></span>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="nk-tb-item">
                                    <div class="nk-tb-col">
                                        <div class="text-center py-4">
                                            <em class="icon ni ni-info"></em>
                                            <p>Chưa có lịch sử nạp tiền nào.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>