<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra user đã đăng nhập chưa
if(!isLogin()){
    echo '<script>alert("Vui lòng đăng nhập!"); window.location.href="/auth/login";</script>';
    exit;
}

// Lấy ID đơn hàng từ URL
$orderId = isset($get[0]) ? (int)$get[0] : 0;

// Lấy thông tin đơn hàng (GIỮ NGUYÊN)
$order = $nify->query("
    SELECT wo.*, 
           wi.name as interface_name, wi.image as interface_image, wi.hosting_price as interface_hosting_price, wi.description as interface_description, wi.features as interface_features, 
           d.name as dot_name, d.price as dot_price, d.renewal_price as dot_renewal_price
    FROM `website_orders` wo 
    LEFT JOIN `website_interfaces` wi ON wo.interface_id = wi.id 
    LEFT JOIN `dots` d ON wo.dot_id = d.id 
    WHERE wo.id = $orderId AND wo.user_id = ".$getUser['id']
)->fetch_assoc();

if(!$order){
    echo '<script>alert("Đơn hàng không tồn tại!"); window.location.href="/manage/web";</script>';
    exit;
}

$renewalHistory = $nify->query("SELECT * FROM `renewal_history` WHERE `order_id` = $orderId ORDER BY `created_at` DESC");
?>

<div class="nk-content nk-content-fluid">
  <div class="container-xl wide-xl">
    <div class="nk-content-body">

      <!-- Header theo DashLite -->
      <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between g-3">
          <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">
              Đơn Hàng / <strong class="text-primary small">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></strong>
            </h3>
            <div class="nk-block-des text-soft">
              <ul class="list-inline">
                <li>Tên miền: <span class="text-base"><?= htmlspecialchars($order['domain']) ?></span></li>
                <li>Ngày đặt: <span class="text-base"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span></li>
              </ul>
            </div>
          </div>
          <div class="nk-block-head-content">
            <a href="/manage/web" class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
              <em class="icon ni ni-arrow-left"></em><span>Quay lại</span>
            </a>
            <a href="/manage/web" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none">
              <em class="icon ni ni-arrow-left"></em>
            </a>
          </div>
        </div>
      </div>

      <div class="nk-block">
        <div class="card">
          <div class="card-aside-wrap">
            <div class="card-content">

              <!-- Tabs DashLite -->
              <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                <li class="nav-item">
                  <a class="nav-link active" data-bs-toggle="tab" href="#tabInfo">
                    <em class="icon ni ni-user-circle"></em><span>Thông tin dịch vụ</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="tab" href="#tabRenew">
                    <em class="icon ni ni-repeat"></em><span>Gia hạn dịch vụ</span>
                  </a>
                </li>
              </ul>

              <div class="tab-content">

                <!-- TAB 1: THÔNG TIN (GIỮ LOGIC HIỂN THỊ GỐC, chỉ đổi bố cục) -->
                <div class="tab-pane active" id="tabInfo">
                  <div class="card-inner">
                    <div class="nk-block-head">
                      <h5 class="title">Thông tin website</h5>
                      <p>Quản lý thông tin và thời hạn dịch vụ.</p>
                    </div>

                    <div class="profile-ud-list">
                      <div class="profile-ud-item">
                        <div class="profile-ud wider">
                          <span class="profile-ud-label">Tên miền</span>
                          <span class="profile-ud-value">
                            <a href="http://<?= $order['domain'] ?>" target="_blank"><?= $order['domain'] ?></a>
                          </span>
                        </div>
                      </div>

                      <div class="profile-ud-item">
                        <div class="profile-ud wider">
                          <span class="profile-ud-label">Giao diện</span>
                          <span class="profile-ud-value"><?= $order['interface_name'] ?></span>
                        </div>
                      </div>

                      <div class="profile-ud-item">
                        <div class="profile-ud wider">
                          <span class="profile-ud-label">Thời gian thuê</span>
                          <span class="profile-ud-value"><?= getMonthsText($order['months']) ?></span>
                        </div>
                      </div>

                      <div class="profile-ud-item">
                        <div class="profile-ud wider">
                          <span class="profile-ud-label">Thời gian còn lại (Hosting)</span>
                          <span class="profile-ud-value text-<?= (new DateTime($order['expiry_date']) < new DateTime()) ? 'danger' : 'success' ?>">
                            <?= getDaysLeft($order['expiry_date']) ?>
                          </span>
                        </div>
                      </div>

                      <div class="profile-ud-item">
                        <div class="profile-ud wider">
                          <span class="profile-ud-label">Hết hạn tên miền</span>
                          <span class="profile-ud-value text-<?= (new DateTime($order['domain_expiry_date']) < new DateTime()) ? 'danger' : 'success' ?>">
                            <?= date('d/m/Y H:i', strtotime($order['domain_expiry_date'])) ?>
                          </span>
                        </div>
                      </div>

                      <div class="profile-ud-item">
                        <div class="profile-ud wider">
                          <span class="profile-ud-label">Tổng giá</span>
                          <span class="profile-ud-value text-primary h6">
                            <?= number_format($order['price'], 0, ',', '.') ?>đ
                          </span>
                        </div>
                      </div>
                      
                      <div class="profile-ud-item">
                        <div class="profile-ud wider">
                          <span class="profile-ud-label">Trạng thái</span>
                          <span class="profile-ud-value text-primary h6">
                            <?=getWebsiteStatus($order['status']);?>
                          </span>
                        </div>
                      </div>
                      
                    </div>

                    <?php if($order['interface_description']): ?>
                    <div class="nk-block-head nk-block-head-line mt-3">
                      <h6 class="title text-base">Mô tả giao diện</h6>
                    </div>
                    <p><?= $order['interface_description'] ?></p>
                    <?php endif; ?>

                    <?php if($order['interface_features']): ?>
                    <div class="nk-block-head nk-block-head-line mt-3">
                      <h6 class="title text-base">Tính năng</h6>
                    </div>
                    <div class="row g-2">
                      <?php foreach (explode(', ', $order['interface_features']) as $feature): ?>
                        <div class="col-md-6">
                          <div class="d-flex align-items-center">
                            <em class="icon ni ni-check-circle text-success me-2"></em>
                            <span><?= trim($feature) ?></span>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                  </div>

                  <!-- Khối phải: Thông tin gia hạn + Lịch sử (đưa vào dưới cùng để vẫn “1 tab”) -->
                  <div class="card-inner">
                    <div class="row g-gs">
                      <div class="col-lg-6">
                        <div class="nk-block-head">
                          <h6 class="title">Thông tin gia hạn</h6>
                        </div>
                        <div class="timeline-list">
                          <div class="timeline-item">
                            <div class="timeline-status bg-success"></div>
                            <div class="timeline-content" style="margin-left: 0.8rem;">
                              <div class="timeline-title">Hosting</div>
                              <div class="timeline-text"> <?= getDaysLeft($order['expiry_date']) ?></div>
                            </div>
                          </div>
                          <div class="timeline-item">
                            <div class="timeline-status bg-<?= (new DateTime($order['domain_expiry_date']) < new DateTime()) ? 'danger' : 'info' ?>"></div>
                            <div class="timeline-content" style="margin-left: 0.8rem;">
                              <div class="timeline-title">Tên miền</div>
                              <div class="timeline-text"> <?= getDaysLeft($order['domain_expiry_date']) ?></div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="nk-block-head">
                          <h6 class="title">Lịch sử gia hạn</h6>
                        </div>
                        <?php if($renewalHistory->num_rows > 0): ?>
                          <div class="timeline-list">
                            <?php while($renewal = $renewalHistory->fetch_assoc()): ?>
                              <div class="timeline-item">
                                <div class="timeline-status bg-primary"></div>
                                <div class="timeline-content" style="margin-left: 0.8rem;">
                                  <div class="timeline-title">Gia hạn <?= (int)$renewal['months'] ?> tháng</div>
                                  <div class="timeline-text"><?= number_format($renewal['amount'], 0, ',', '.') ?>đ</div>
                                  <div class="timeline-date"><?= date('d/m/Y', strtotime($renewal['created_at'])) ?></div>
                                </div>
                              </div>
                            <?php endwhile; ?>
                          </div>
                        <?php else: ?>
                          <div class="text-muted"><em class="icon ni ni-info"></em> Chưa có lịch sử gia hạn</div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane" id="tabRenew">
                  <div class="card-inner">
                    <div class="nk-block-head"><h5 class="title">Gia hạn dịch vụ</h5></div>

                    <form id="renewForm">
                      <div class="row g-3">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="form-label">Chọn thời gian gia hạn</label>
                            <div class="form-control-wrap">
                              <select class="form-select" name="months" id="renewMonths" required>
                                <option value="">-- Chọn thời gian --</option>
                                <option value="1">1 tháng</option>
                                <option value="3">3 tháng</option>
                                <option value="6">6 tháng</option>
                                <option value="12">12 tháng</option>
                                <option value="24">24 tháng (Miền x2 năm)</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="form-group">
                            <div class="custom-control custom-control-sm custom-checkbox">
                              <input type="checkbox" class="custom-control-input" id="renewDomain" name="renew_domain" checked>
                              <label class="custom-control-label" for="renewDomain">
                                Gia hạn tên miền (<?= number_format($order['dot_renewal_price'], 0, ',', '.') ?>đ/năm)
                              </label>
                            </div>
                            <small class="text-muted" id="domainNote">
                              Tên miền sẽ được gia hạn tự động khi hết hạn
                            </small>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="alert alert-info">
                            <em class="icon ni ni-info"></em>
                            <div id="renewSummary">
                              <strong>Chi tiết gia hạn:</strong><br>
                              Hosting: <span id="hostingPrice">0đ</span><br>
                              Tên miền: <span id="domainPrice">0đ</span><br>
                              <div id="renewDomainInfo"></div>
                              <strong>Tổng cộng: <span id="totalRenewPrice">0đ</span></strong>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <button type="submit" class="btn btn-primary">
                            <em class="icon ni ni-refresh"></em> <span>Xác nhận gia hạn</span>
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

              </div><!-- .tab-content -->
            </div><!-- .card-content -->
          </div><!-- .card-aside-wrap -->
        </div><!-- .card -->
      </div><!-- .nk-block -->

    </div>
  </div>
</div>

<script>
document.getElementById('renewMonths').addEventListener('change', updateRenewPrice);

function updateRenewPrice(){
    const months = parseInt(document.getElementById('renewMonths').value) || 0;

    // Giá hosting (giả sử định giá là 200,000đ/tháng) — GIỮ NHƯ GỐC
    const hostingPricePerMonth = <?=(int) $order['interface_hosting_price'] ?>;
    const hostingPrice = hostingPricePerMonth * months;

    // Logic tự động tính tiền miền — GIỮ LOGIC
    const domainExpiryDate = new Date('<?= $order['domain_expiry_date'] ?>');
    const currentDate = new Date();
    const daysUntilDomainExpiry = Math.floor((domainExpiryDate - currentDate) / (1000 * 60 * 60 * 24));

    let domainPrice = 0;
    let domainYears = 1; // Mặc định 1 năm
    
    if(months > 12){
        domainYears = 2;
    }
    
    let shouldRenewDomain = false;

    // Chỉ gia hạn tên miền khi đã hết hạn — GIỮ LOGIC
    if(daysUntilDomainExpiry <= 0){
        shouldRenewDomain = true;
        // Luôn luôn gia hạn 1 năm khi hết hạn — GIỮ NHƯ GỐC
    }

    if(shouldRenewDomain){
        domainPrice = <?= (int)$order['dot_renewal_price'] ?> * domainYears;
    }

    // Tổng
    const total = hostingPrice + domainPrice;

    // Cập nhật UI — GIỮ CÁC ID GỐC
    document.getElementById('hostingPrice').textContent = hostingPrice.toLocaleString('vi-VN') + 'đ';
    document.getElementById('domainPrice').textContent = domainPrice.toLocaleString('vi-VN') + 'đ';
    document.getElementById('totalRenewPrice').textContent = total.toLocaleString('vi-VN') + 'đ';

    // Hiển thị thông tin tên miền — GIỮ LOGIC & ID
    const domainInfo = document.getElementById('renewDomainInfo');
    const domainNote = document.getElementById('domainNote');
    const renewDomainCheckbox = document.getElementById('renewDomain');

    if(domainInfo && domainNote && renewDomainCheckbox){
        if(daysUntilDomainExpiry <= 0){
            if(months <= 12){
                domainInfo.innerHTML = `<small class="text-info">Tên miền: 1 năm</small>`;
                domainNote.innerHTML = `<small class="text-warning">⚠️ Tên miền đã hết hạn, sẽ được gia hạn tự động</small>`;
            } else {
                domainInfo.innerHTML = `<small class="text-warning">Tên miền: 2 năm</small>`;
                domainNote.innerHTML = `<small class="text-warning">⚠️ Tên miền đã hết hạn, sẽ được gia hạn tự động</small>`;
            }
        } else {
            // Ước lượng số tháng còn lại (để gần với chữ “tháng” trong LOGIC cũ)
            const monthsLeft = Math.max(0, Math.floor(daysUntilDomainExpiry / 30));
            if(monthsLeft > 0){
                domainInfo.innerHTML = `<small class="text-success">Tên miền: Còn khoảng ${monthsLeft} tháng</small>`;
                domainNote.innerHTML = `<small class="text-success">Tên miền còn ${monthsLeft} tháng, chưa cần gia hạn</small>`;
            } else {
                domainInfo.innerHTML = `<small class="text-warning">Tên miền sắp hết hạn</small>`;
                domainNote.innerHTML = `<small class="text-warning">Tên miền sắp hết hạn</small>`;
            }
        }

        // GIỮ QUYẾT ĐỊNH: Disable checkbox - không cho user thay đổi
        renewDomainCheckbox.disabled = true;
    }
}

// Xử lý submit form gia hạn — GIỮ NGUYÊN ENDPOINT & LOGIC
document.getElementById('renewForm').addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('order_id', <?= (int)$order['id'] ?>);
    formData.append('csrf_token', '<?= csrf_generate_token() ?>');

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<em class="icon ni ni-loading spinner"></em> Đang xử lý...';
    submitBtn.disabled = true;

    fetch('/ajaxs/client/website/renew', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.status){
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Có lỗi xảy ra, vui lòng thử lại!');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
