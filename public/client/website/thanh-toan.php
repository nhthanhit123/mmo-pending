<?php
$interfaceId = isset($get[0]) ? (int)$get[0] : 0;

if(!isLogin()) {
    echo redirect('/auth/login');
    exit();
}

$interface = $nify->query("SELECT * FROM `website_interfaces` WHERE `id` = $interfaceId AND `status` = 'active'")->fetch_assoc();
if(!$interface) {
    echo '<script>alert("Giao diện không tồn tại!"); window.location.href="/kho-giao-dien";</script>';
    exit;
}

$dots = $nify->query("SELECT * FROM `dots` WHERE `status` = 'active' ORDER BY `price` ASC");
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
    <div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between g-3">
    <div class="nk-block-head-content">
    <h3 class="nk-block-title page-title">Thanh Toán Thuê Website</h3>
    <div class="nk-block-des text-soft">
        <p>Hoàn tất thông tin để thuê website <?= $interface['name'] ?></p>
    </div>
</div>
<div class="nk-block-head-content">
    <a href="/kho-giao-dien" class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
    <em class="icon ni ni-arrow-left"></em>
    <span>Quay lại</span>
    </a>
    <a href="/kho-giao-dien" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none">
    <em class="icon ni ni-arrow-left"></em>
    </a>
</div>
</div>
</div>

<div class="nk-block">
    <div class="card">
    <div class="card-inner">
    <div class="row g-5">
    <div class="col-lg-8">
        <form method="POST" id="orderForm">
            <div class="nk-block">
                <div class="nk-block-head">
                    <h5 class="nk-block-title">Thông tin giao diện</h5>
                </div>
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="product-info">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <img src="<?= $interface['image'] ?: 'https://via.placeholder.com/300x200' ?>" 
                                         alt="<?= $interface['name'] ?>" class="img-fluid rounded">
                                </div>
                                <div class="col-md-9">
                                    <h4><?= $interface['name'] ?></h4>
                                    <p class="text-muted"><?= $interface['description'] ?></p>
                                    <div class="product-price text-primary h4">
                                        <?php if($interface['sale_price'] && $interface['sale_price'] < $interface['price']): ?>
                                            <small class="text-muted del fs-14px"><?= number_format($interface['price'], 0, ',', '.') ?>đ</small>
                                            <?= number_format($interface['sale_price'], 0, ',', '.') ?>đ
                                        <?php else: ?>
                                            <?= number_format($interface['price'], 0, ',', '.') ?>đ
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-info"><?= $interface['category'] ?></span>
                                        <?php if($interface['is_featured']): ?>
                                            <span class="badge bg-success">Nổi bật</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nk-block">
                <div class="nk-block-head">
                    <h5 class="nk-block-title">Thời gian thuê</h5>
                </div>
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-pro no-control checked">
                                        <input type="radio" class="custom-control-input" name="months" id="months_1" value="1" checked>
                                        <label class="custom-control-label" for="months_1">
                                            <div class="fw-bold">1 Tháng</div>
                                            <sup class="text-muted small"> Giá gốc </sup>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-pro no-control">
                                        <input type="radio" class="custom-control-input" name="months" id="months_3" value="3">
                                        <label class="custom-control-label" for="months_3">
                                            <div class="fw-bold">3 Tháng</div>
                                            <sup class="text-muted small"> - <?= $interface['discount_3_months'] ?>%</sup>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-pro no-control">
                                        <input type="radio" class="custom-control-input" name="months" id="months_6" value="6">
                                        <label class="custom-control-label" for="months_6">
                                            <div class="fw-bold">6 Tháng</div>
                                            <sup class="text-muted small"> - <?= $interface['discount_6_months'] ?>%</sup>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-pro no-control">
                                        <input type="radio" class="custom-control-input" name="months" id="months_12" value="12">
                                        <label class="custom-control-label" for="months_12">
                                            <div class="fw-bold">12 Tháng</div>
                                            <sup class="text-success small"> - <?= $interface['discount_12_months'] ?>% 🔥</sup>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-pro no-control">
                                        <input type="radio" class="custom-control-input" name="months" id="months_24" value="24">
                                        <label class="custom-control-label" for="months_24">
                                            <div class="fw-bold">24 Tháng</div>
                                            <sup class="text-warning small"> - <?= $interface['discount_12_months'] ?>% 🔥</sup>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nk-block">
                <div class="nk-block-head">
                    <h5 class="nk-block-title">Thông tin tên miền</h5>
                </div>
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label">Tên miền</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="domain" 
                                               placeholder="Nhập tên miền (ví dụ: tenwebsite)" required>
                                        <small class="form-text">Không bao gồm đuôi miền, chỉ nhập tên chính</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Đuôi miền</label>
                                    <div class="form-control-wrap">
                                        <select class="form-select" name="dot_id" id="dotSelect" required>
                                            <option value="">-- Chọn đuôi miền --</option>
                                            <?php while($dot = $dots->fetch_assoc()): ?>
                                            <option value="<?= $dot['id'] ?>" data-price="<?= $dot['price'] ?>">
                                                <?= $dot['name'] ?> - <?= number_format($dot['price'], 0, ',', '.') ?>đ/năm
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="preview-domain">
                                <strong>Tên miền hoàn chỉnh: </strong>
                                <span id="fullDomain" class="text-primary">tenwebsite.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nk-block">
                <div class="nk-block-head">
                    <h5 class="nk-block-title">Phương thức thanh toán</h5>
                </div>
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="form-group">
                            <div class="custom-control custom-radio custom-control-pro no-control checked">
                                <input type="radio" class="custom-control-input" name="payment_method" id="payment_wallet" checked>
                                <label class="custom-control-label" for="payment_wallet">
                                    <em class="icon ni ni-wallet"></em>
                                    Thanh toán từ ví tài khoản
                                    <div class="text-muted fs-12px">
                                        Số dư hiện tại: <strong class="text-success"><?= number_format($getUser['balance'], 0, ',', '.') ?>đ</strong>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="nk-block">
            <div class="nk-block-head">
                <h5 class="nk-block-title">Tổng đơn hàng</h5>
            </div>
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="order-summary">
                        <div class="summary-item">
                            <div class="summary-label">Giá giao diện:</div>
                            <div class="summary-value" id="interfacePrice">
                                <?= number_format($interface['sale_price'] ?: $interface['price'], 0, ',', '.') ?>đ
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Số Tháng thuê:</div>
                            <div class="summary-value" id="selectedMonths">1 Tháng</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Giá tên miền:</div>
                            <div class="summary-value">
                                <div id="domainPrice">0đ</div>
                                <div id="domainInfo"><small class="text-muted">Tên miền: 1 năm</small></div>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Chiết khấu:</div>
                            <div class="summary-value text-success" id="discount">0đ</div>
                        </div>
                        <hr>
                        <div class="summary-item summary-total">
                            <div class="summary-label">Tổng cộng:</div>
                            <div class="summary-value text-primary h4" id="totalPrice">
                                <?= number_format($interface['sale_price'] ?: $interface['price'], 0, ',', '.') ?>đ
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <em class="icon ni ni-info"></em>
                                Tiết kiệm <span id="savingAmount">0đ</span> khi thuê dài hạn
                            </small>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" onclick="submitOrder()" class="btn btn-primary btn-lg btn-block">
                            <em class="icon ni ni-cart"></em>
                            Xác nhận đặt hàng
                        </button>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            Bằng cách nhấp vào "Xác nhận đặt hàng", bạn đồng ý với 
                            <a href="#" class="text-primary">điều khoản dịch vụ</a> của chúng tôi
                        </small>
                    </div>
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
    const domainInput = document.querySelector('input[name="domain"]');
    const dotSelect = document.getElementById('dotSelect');
    const fullDomain = document.getElementById('fullDomain');
    const domainPrice = document.getElementById('domainPrice');
    const totalPrice = document.getElementById('totalPrice');
    const interfacePrice = document.getElementById('interfacePrice');
    const selectedMonths = document.getElementById('selectedMonths');
    const discount = document.getElementById('discount');
    const savingAmount = document.getElementById('savingAmount');
    
    const interfacePriceValue = <?= (int)($interface['sale_price'] ?: $interface['price']) ?>;
    
    // Discount rates from database
    const discountRates = {
        1: 0,
        3: <?= $interface['discount_3_months'] / 100 ?>,
        6: <?= $interface['discount_6_months'] / 100 ?>,
        12: <?= $interface['discount_12_months'] / 100 ?>,
        24: <?= $interface['discount_12_months'] / 100 ?> // 24 Tháng cùng chiết khấu với 12 Tháng
    };
    
    function updateDomain() {
        const domain = domainInput.value.trim();
        const selectedOption = dotSelect.options[dotSelect.selectedIndex];
        const dot = selectedOption.text.split(' - ')[0] || '.com';
        
        if(domain) {
            fullDomain.textContent = domain + dot;
        } else {
            fullDomain.textContent = 'tenwebsite' + dot;
        }
    }
    
    function updatePrice() {
        const selectedOption = dotSelect.options[dotSelect.selectedIndex];
        const dotPrice = parseInt(selectedOption.dataset?.price || 0);
        
        // Get selected months
        const selectedMonthsInput = document.querySelector('input[name="months"]:checked');
        const months = parseInt(selectedMonthsInput?.value || 1);
        
        // Calculate discount
        const discountRate = discountRates[months] || 0;
        const discountAmount = interfacePriceValue * discountRate;
        const discountedInterfacePrice = interfacePriceValue - discountAmount;
        
        // Calculate total
        const totalInterfacePrice = discountedInterfacePrice * months;
        
        // Domain price calculation
        let totalDomainPrice = dotPrice; // Default 1 year
        if (months >= 24) {
            totalDomainPrice = dotPrice * 2; // 2 years for 24+ months
        }
        
        const total = totalInterfacePrice + totalDomainPrice;
        
        // Update UI
        domainPrice.textContent = totalDomainPrice ? totalDomainPrice.toLocaleString('vi-VN') + 'đ' : '0đ';
        selectedMonths.textContent = months + ' Tháng';
        discount.textContent = discountAmount ? '-' + discountAmount.toLocaleString('vi-VN') + 'đ' : '0đ';
        totalPrice.textContent = total.toLocaleString('vi-VN') + 'đ';
        
        // Update saving amount
        const originalTotal = (interfacePriceValue * months) + totalDomainPrice;
        const saving = originalTotal - total;
        savingAmount.textContent = saving ? saving.toLocaleString('vi-VN') + 'đ' : '0đ';
        
        // Show domain info
        const domainInfo = document.getElementById('domainInfo');
        if (domainInfo) {
            if (months >= 24) {
                domainInfo.innerHTML = '<small class="text-info">Tên miền: 2 năm</small>';
            } else {
                domainInfo.innerHTML = '<small class="text-muted">Tên miền: 1 năm</small>';
            }
        }
    }
    
    function updateMonths() {
        updatePrice();
    }
    
    // Event listeners
    domainInput.addEventListener('input', updateDomain);
    dotSelect.addEventListener('change', function() {
        updateDomain();
        updatePrice();
    });
    
    // Add event listeners for months radio buttons
    document.querySelectorAll('input[name="months"]').forEach(radio => {
        radio.addEventListener('change', updateMonths);
    });
    
    // Initialize
    updateDomain();
    updatePrice();
});

function submitOrder() {
    const form = document.getElementById('orderForm');
    const formData = new FormData(form);
    
    // Thêm interface_id vào form data
    formData.append('interface_id', <?= $interfaceId ?>);
    // Thêm CSRF token
    formData.append('csrf_token', '<?= csrf_generate_token() ?>');
    
    // Validate form
    const domain = formData.get('domain');
    const dotId = formData.get('dot_id');
    const months = formData.get('months');
    
    if(!domain || !dotId || !months) {
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
    }
    
    // Hiển thị loading
    const submitBtn = document.querySelector('button[onclick="submitOrder()"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<em class="icon ni ni-loading spinner"></em> Đang xử lý...';
    submitBtn.disabled = true;
    
    // Gửi AJAX request
    fetch('/ajaxs/client/website/thanh-toan', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status) {
            alert(data.message);
            window.location.href = data.redirect;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra, vui lòng thử lại!');
    })
    .finally(() => {
        // Khôi phục button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
</script>