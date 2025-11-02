<?php
// Kiểm tra user đã đăng nhập chưa
if(!isLogin()) {
    echo '<script>alert("Vui lòng đăng nhập!"); window.location.href="/auth/login";</script>';
    exit;
}

// Lấy danh sách đơn hàng của user
$getOrders = $nify->query("SELECT wo.*, wi.name as interface_name, wi.image as interface_image, d.name as dot_name 
                          FROM `website_orders` wo 
                          LEFT JOIN `website_interfaces` wi ON wo.interface_id = wi.id 
                          LEFT JOIN `dots` d ON wo.dot_id = d.id 
                          WHERE wo.user_id = ".$getUser['id']." 
                          ORDER BY wo.created_at DESC");
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
    <div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
    <div class="nk-block-head-content">
    <h3 class="nk-block-title page-title">Trang Web Của Tôi</h3>
    <div class="nk-block-des text-soft">
        <p>Bạn có tổng <?= $getOrders->num_rows ?> website đã thuê.</p>
    </div>
</div>
<div class="nk-block-head-content">
    <div class="toggle-wrap nk-block-tools-toggle">
    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
    <em class="icon ni ni-menu-alt-r"></em>
</a>
<div class="toggle-expand-content" data-content="pageMenu">
    <ul class="nk-block-tools g-3">
    <li>
    <div class="drodown">
    <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-bs-toggle="dropdown">
    <em class="d-none d-sm-inline icon ni ni-filter-alt"></em>
    <span>Lọc theo</span>
    <em class="dd-indc icon ni ni-chevron-right"></em>
</a>
<div class="dropdown-menu dropdown-menu-end">
    <ul class="link-list-opt no-bdr">
    <li>
        <a href="#" onclick="filterOrders('all')">
            <span>Tất cả</span>
        </a>
    </li>
    <li>
        <a href="#" onclick="filterOrders('pending')">
            <span>Chờ xử lý</span>
        </a>
    </li>
    <li>
        <a href="#" onclick="filterOrders('active')">
            <span>Hoạt động</span>
        </a>
    </li>
    <li>
        <a href="#" onclick="filterOrders('expired')">
            <span>Hết hạn</span>
        </a>
    </li>
    <li>
        <a href="#" onclick="filterOrders('cancelled')">
            <span>Đã hủy</span>
        </a>
    </li>
    </ul>
</div>
</div>
</li>
<li class="nk-block-tools-opt d-none d-sm-block">
    <a href="/kho-giao-dien" class="btn btn-primary">
    <em class="icon ni ni-plus"></em>
    <span>Thuê website mới</span>
    </a>
</li>
<li class="nk-block-tools-opt d-block d-sm-none">
    <a href="/kho-giao-dien" class="btn btn-icon btn-primary">
    <em class="icon ni ni-plus"></em>
    </a>
</li>
</ul>
</div>
</div>
</div>
</div>

<div class="nk-block">
    <div class="card card-stretch">
    <div class="card-inner p-0">
        <?php if($getOrders->num_rows > 0): ?>
        <div class="nk-tb-list nk-tb-ulist is-compact">
            <div class="nk-tb-item nk-tb-head">
                <div class="nk-tb-col">
                    <span class="sub-text">Thông tin website</span>
                </div>
                <div class="nk-tb-col tb-col-md">
                    <span class="sub-text">Tên miền</span>
                </div>
                <div class="nk-tb-col tb-col-lg">
                    <span class="sub-text">Giá</span>
                </div>
                <div class="nk-tb-col tb-col-lg">
                    <span class="sub-text">Trạng thái</span>
                </div>
                <div class="nk-tb-col tb-col-md">
                    <span class="sub-text">Thời gian còn lại</span>
                </div>
                <div class="nk-tb-col tb-col-md">
                    <span class="sub-text">Ngày đặt</span>
                </div>
                <div class="nk-tb-col tb-col-md">
                    <span class="sub-text">Hết Hạn</span>
                </div>
                <div class="nk-tb-col nk-tb-col-tools text-end">
                    <span class="sub-text">Hành động</span>
                </div>
            </div>
            
            <?php while($order = $getOrders->fetch_assoc()): ?>
            <div class="nk-tb-item order-item" data-status="<?= $order['status'] ?>">
                <div class="nk-tb-col">
                    <div class="user-card">
                        <div class="user-avatar sq bg-primary">
                            <?php if($order['interface_image']): ?>
                                <img src="<?= $order['interface_image'] ?>" alt="<?= $order['interface_name'] ?>">
                            <?php else: ?>
                                <span><?= substr($order['interface_name'], 0, 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="user-info">
                            <span class="lead-text"><?= $order['interface_name'] ?></span>
                            <span class="sub-text">Mã đơn: #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
                            <a href="/manage/web-order/<?= $order['id'] ?>" class="text-primary">
                                <small>Xem chi tiết →</small>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="nk-tb-col tb-col-md">
                    <span class="tb-lead"><?= $order['domain'] ?></span>
                </div>
                
                <div class="nk-tb-col tb-col-lg">
                    <span class="tb-lead text-primary"><?= number_format($order['price'], 0, ',', '.') ?>đ</span>
                </div>
                
                <div class="nk-tb-col tb-col-lg">
                    <?= getWebsiteStatus($order['status']) ?>
                </div>
                
                <div class="nk-tb-col tb-col-md">
                    <span class="tb-sub text-<?= $order['expiry_date'] && (new DateTime($order['expiry_date']) < new DateTime()) ? 'danger' : 'success' ?>">
                        <?= getDaysLeft($order['expiry_date']) ?>
                    </span>
                </div>
                
                <div class="nk-tb-col tb-col-md">
                    <span class="tb-sub"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                </div>
                
                <div class="nk-tb-col tb-col-md">
                    <span class="tb-sub"><?= date('d/m/Y', strtotime($order['expiry_date'])) ?></span>
                </div>
                
                <div class="nk-tb-col nk-tb-col-tools">
                    <ul class="nk-tb-actions gx-1">
                        <li>
                            <div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-bs-toggle="dropdown">
                                    <em class="icon ni ni-more-h"></em>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li>
                                            <a href="http://<?= $order['domain'] ?>" target="_blank">
                                                <em class="icon ni ni-eye"></em>
                                                <span>Xem website</span>
                                            </a>
                                        </li>
                                        
                                        <?php if(in_array($order['status'], ['pending', 'processing'])): ?>
                                        <li>
                                            <a href="#" onclick="cancelOrder(<?= $order['id'] ?>)">
                                                <em class="icon ni ni-cross"></em>
                                                <span>Hủy đơn</span>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <div class="nk-block-content">
                <em class="icon ni ni-globe icon-3x text-muted mb-3"></em>
                <h4 class="nk-block-title">Bạn chưa thuê website nào</h4>
                <p class="text-muted">Hãy chọn một giao diện đẹp và bắt đầu xây dựng website của riêng bạn!</p>
                <a href="/kho-giao-dien" class="btn btn-primary mt-3">
                    <em class="icon ni ni-plus"></em>
                    <span>Thuê website ngay</span>
                </a>
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

<script>
function filterOrders(status) {
    const items = document.querySelectorAll('.order-item');
    items.forEach(item => {
        if (status === 'all' || item.dataset.status === status) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

function cancelOrder(orderId) {
    if(confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        // Gửi AJAX request để hủy đơn
        fetch('/ajaxs/client/website/cancel-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&csrf_token=<?= csrf_generate_token() ?>`
        })
        .then(response => response.json())
        .then(data => {
            if(data.status) {
                alert('Đã hủy đơn hàng thành công!');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        });
    }
}

function renewWebsite(orderId) {
    if(confirm('Bạn có muốn gia hạn website này?')) {
        // Chuyển đến trang gia hạn
        window.location.href = '/website/renew/' + orderId;
    }
}
</script>