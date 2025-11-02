<?php
// Get statistics
$totalUsers = $nify->query("SELECT COUNT(*) as count FROM `users` WHERE `status` = 'active'")->fetch_assoc()['count'];
$totalDeposits = $nify->query("SELECT COUNT(*) as count FROM `deposits` WHERE `status` = 'success'")->fetch_assoc()['count'];
$totalRevenue = $nify->query("SELECT SUM(amount) as total FROM `deposits` WHERE `status` = 'success'")->fetch_assoc()['total'];
$todayRevenue = $nify->query("SELECT SUM(amount) as total FROM `deposits` WHERE `status` = 'success' AND DATE(created_at) = CURDATE()")->fetch_assoc()['total'];

// Get recent deposits
$recentDeposits = $nify->query("SELECT d.*, u.username FROM `deposits` d JOIN `users` u ON d.user_id = u.id WHERE d.status = 'success' ORDER BY d.created_at DESC LIMIT 5");

// Get site settings
$siteName = echoNify('site_name') ?: 'MMO Platform';
$siteDescription = echoNify('site_description') ?: 'Nền tảng MMO uy tín hàng đầu';
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        
        <div class="nk-content-body">
            <div class="nk-block-head nk-page-head nk-block-head-sm">
            <div class="nk-block-head-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Xin Chào <?=(x($getUser['username'] ?? 'Khách'));?>!</h3>
            </div>
            </div>
            </div>
        <div class="nk-block">
            <div class="row g-gs">
            <div class="col-sm-6 col-6 col-xxl-3">
            <div class="card card-full bg-primary">
            <div class="card-inner">
            <div class="d-flex align-items-center justify-content-between mb-1">
            <div class="fs-6 text-white text-opacity-75 mb-0">Số Dư</div>
            <a href="/deposit" class="link link-white">Nạp Tiền</a>
            </div>
            <h6 class="fs-1 text-white"> 500,000 <small class="fs-3">đ</small>
            </h6>
        <div class="fs-7 text-white text-opacity-75 mt-1">
            <span class="text-white">Tiêu 500,000đ</span>/1,000,000đ -> VIP1</div>
        </div>
        </div>
        </div>
        <div class="col-sm-6 col-6 col-xxl-3">
            <div class="card card-full bg-warning is-dark">
            <div class="card-inner">
            <div class="d-flex align-items-center justify-content-between mb-1">
            <div class="fs-6 text-white text-opacity-75 mb-0">Hóa Đơn</div>
            <a href="/invoices" class="link link-white">Xem Ngay</a>
            </div>
            <h6 class="fs-1 text-white">3 <small class="fs-3">INV</small>
            </h6>
        <div class="fs-7 text-white text-opacity-75 mt-1">
            <span class="text-white">Giảm Đến 5% Khi Thanh Toán Sớm</div>
        </div>
        </div>
        </div>
        <div class="col-sm-6 col-6 col-xxl-3">
            <div class="card card-full bg-info is-dark">
            <div class="card-inner">
            <div class="d-flex align-items-center justify-content-between mb-1">
            <div class="fs-6 text-white text-opacity-75 mb-0">Đang Dùng</div>
            <a href="/manage/services" class="link link-white">Xem Ngay</a>
            </div>
            <h6 class="fs-1 text-white">6 <small class="fs-3"> SERV </small>
            </h6>
        <div class="fs-7 text-white text-opacity-75 mt-1">
            <span class="text-white">Tặng Lì Xì Khi Đạt 15 VIP Serv/ Tháng</div>
        </div>
        </div>
        </div>
        <div class="col-sm-6 col-6 col-xxl-3">
            <div class="card card-full bg-danger is-dark">
            <div class="card-inner">
            <div class="d-flex align-items-center justify-content-between mb-1">
            <div class="fs-6 text-white text-opacity-75 mb-0">Cấp Bậc</div>
            <a href="/levels" class="link link-white">Nâng Cấp</a>
            </div>
            <h6 class="fs-1 text-white">VIP <small class="fs-3">1</small>
            </h6>
        <div class="fs-7 text-white text-opacity-75 mt-1">
            <span class="text-white">Nâng Cấp </span> Để Có Giá Tốt</div>
        </div>
        </div>
        </div>
        </div>
        </div>
        
        
        <div class="nk-block-head">
            <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title"> Đăng Ký Dịch Vụ </h4>
            </div>
        <div class="nk-block-head-content">
            <a href="/services" class="link">Xem Tất Cả</a>
        </div>
        </div>
        </div>
        
        <!-- NT Reg Services -->
        <div class="nk-block">
            <div class="row g-gs">
            <div class="col-sm-6 col-xxl-3">
            <div class="card card-full" style="border-top: 4px solid #00c853;">
            <div class="card-inner">
                
            <div class="kanban-item-title mb-2">
                <h6 class="title">Thuê Website</h6>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar-group">
                            <div class="user-avatar xs bg-danger"><span>VIP</span></div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <ul class="link-list-opt no-bdr p-3 g-2">
                            <li>
                                <div class="user-card">
                                    <div class="user-avatar sm bg-danger">
                                        <span>VIP</span>
                                    </div>
                                    <div class="user-name">
                                        <span class="tb-lead"> Dịch Vụ Cao Cấp </span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
                
            <p>Tạo Trang Web MMO Theo Mẫu Bạn Yêu Thích!</p>
            <ul class="kanban-item-tags">
                                <li><a href=""><span class="badge bg-success"><em class="ni ni-chevron-right-c mr-2" style="margin-top: 3px;"></em>Xem Ngay</span></a></li>
                                <li><a href=""><span class="badge bg-dark"><em class="ni ni-support mr-2" style="margin-top: 3px;"></em>Tư Vấn</span></a></li>
                            </ul>
            </div>
            </div>
            </div>
            
            <div class="col-sm-6 col-xxl-3">
                <div class="card card-full" style="border-top: 4px solid #00c853;">
                <div class="card-inner">
                <div class="position-absolute end-0 top-0 me-4 mt-4">
                </div>
                
                <div class="kanban-item-title mb-2">
                <h6 class="title">Mua Mã Nguồn</h6>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar-group">
                            <div class="user-avatar xs bg-danger"><span>VIP</span></div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <ul class="link-list-opt no-bdr p-3 g-2">
                            <li>
                                <div class="user-card">
                                    <div class="user-avatar sm bg-danger">
                                        <span>VIP</span>
                                    </div>
                                    <div class="user-name">
                                        <span class="tb-lead"> Dịch Vụ Cao Cấp </span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <p>Mã Nguồn Phù Hợp Cho Website Của Bạn Với Mức Giá Siêu Rẻ!</p>
            <ul class="kanban-item-tags">
                                <li><a href=""><span class="badge bg-success"><em class="ni ni-chevron-right-c mr-2" style="margin-top: 3px;"></em>Xem Ngay</span></a></li>
                                <li><a href=""><span class="badge bg-dark"><em class="ni ni-support mr-2" style="margin-top: 3px;"></em>Tư Vấn</span></a></li>
                            </ul>
            </div>
            </div>
            </div>
            
            
        <div class="col-sm-6 col-xxl-3">
            <div class="card card-full" style="border-top: 4px solid #00c853;">
            <div class="card-inner">
            
                        <div class="kanban-item-title mb-2">
                <h6 class="title">Thiết Kế Web Độc Quyền</h6>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar-group">
                            <div class="user-avatar xs bg-danger"><span>VIP</span></div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <ul class="link-list-opt no-bdr p-3 g-2">
                            <li>
                                <div class="user-card">
                                    <div class="user-avatar sm bg-danger">
                                        <span>VIP</span>
                                    </div>
                                    <div class="user-name">
                                        <span class="tb-lead"> Dịch Vụ Cao Cấp </span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <p>Tạo Ra Một Trang Web Độc Quyền Cho Riêng Bạn!</p>
            <ul class="kanban-item-tags">
                                <li><a href=""><span class="badge bg-success"><em class="ni ni-chevron-right-c mr-2" style="margin-top: 3px;"></em>Xem Ngay</span></a></li>
                                <li><a href=""><span class="badge bg-dark"><em class="ni ni-support mr-2" style="margin-top: 3px;"></em>Tư Vấn</span></a></li>
                            </ul>
            </div>
            </div>
            </div>
            
            
        <div class="col-sm-6 col-xxl-3">
            <div class="card card-full" style="border-top: 4px solid #00c853;">
            <div class="card-inner">
                
                        <div class="kanban-item-title mb-2">
                <h6 class="title">Code Độc Quyền</h6>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar-group">
                            <div class="user-avatar xs bg-danger"><span>VIP</span></div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <ul class="link-list-opt no-bdr p-3 g-2">
                            <li>
                                <div class="user-card">
                                    <div class="user-avatar sm bg-danger">
                                        <span>VIP</span>
                                    </div>
                                    <div class="user-name">
                                        <span class="tb-lead"> Dịch Vụ Cao Cấp </span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <p>Code Độc Quyền Cho Doanh Nghiệp/Cá Nhân Kinh Doanh Của Bạn!</p>
            <ul class="kanban-item-tags">
                                <li><a href=""><span class="badge bg-success"><em class="ni ni-chevron-right-c mr-2" style="margin-top: 3px;"></em>Xem Ngay</span></a></li>
                                <li><a href=""><span class="badge bg-dark"><em class="ni ni-support mr-2" style="margin-top: 3px;"></em>Tư Vấn</span></a></li>
                            </ul>
            </div>
            </div>
            </div>
            </div>
            </div>


        <!-- NT Popular -->
                        <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title">Tính Năng Nổi Bật</h4>
                        <p>Khám phá các tính năng tuyệt vời của nền tảng chúng tôi</p>
                    </div>
                </div>
                <div class="row g-gs">
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="preview-icon-wrap mb-2" style="height: 6rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90"><rect x="13" y="16" width="15" height="15" rx="6" ry="6" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><rect x="24" y="82" width="11" height="5" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><rect x="60" y="82" width="11" height="5" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><path d="M47,70.15S61.89,62.49,61.89,51V37.6L47,31.85,32.11,37.6V51C32.11,62.49,47,70.15,47,70.15Z" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M41.56,48H52.44A1.6,1.6,0,0,1,54,49.59v5.73A1.6,1.6,0,0,1,52.44,57H41.56A1.6,1.6,0,0,1,40,55.32V49.59A1.6,1.6,0,0,1,41.56,48Z" fill="#6576ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M43,48V45a4,4,0,0,1,8,0v3" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><circle cx="46.67" cy="52.79" r="0.91" fill="#fff" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></circle><circle cx="23" cy="17" r="14" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></circle><circle cx="23" cy="17" r="10.5" fill="#e3e7fe"></circle><path d="M28.46,20.47l-4.41-4.41a3.4,3.4,0,0,0,.26-1.31A3.34,3.34,0,1,0,21,18.1a3.41,3.41,0,0,0,1.31-.27l1.44,1.45a.33.33,0,0,0,.23.09l.79,0,0,.79a.32.32,0,0,0,.09.23.27.27,0,0,0,.23.08l.79,0,0,.79a.31.31,0,0,0,.09.22.29.29,0,0,0,.22.09l.79,0,0,.79a.3.3,0,0,0,.09.24.32.32,0,0,0,.21.08h0l1.21-.14a.3.3,0,0,0,.27-.33l-.13-1.48Z" fill="#6576ff"></path><path d="M20.56,14.09a1,1,0,0,1-1.34,0,1,1,0,0,1,0-1.35,1,1,0,1,1,1.34,1.35Z" fill="#fff"></path><path d="M23.72,16.39h0l3.79,3.79a.22.22,0,0,1,0,.32h0a.24.24,0,0,1-.32,0l-3.75-3.76Z" fill="#fff"></path><rect x="17.32" y="11.6" width="11" height="11" fill="none"></rect></svg></div>
                                <h6 class="title">Bảo Mật Tối Ưu</h6>
                                <p class="text-soft">Hệ thống bảo mật nhiều lớp, mã hóa token MD5, chống XSS và SQL Injection</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="preview-icon-wrap mb-2" style="height: 6rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90"><rect x="9" y="21" width="55" height="39" rx="6" ry="6" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><line x1="17" y1="44" x2="25" y2="44" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="30" y1="44" x2="38" y2="44" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="42" y1="44" x2="50" y2="44" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="17" y1="50" x2="36" y2="50" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><rect x="16" y="31" width="15" height="8" rx="1" ry="1" fill="#c4cefe"></rect><path d="M76.79,72.87,32.22,86.73a6,6,0,0,1-7.47-4L17,57.71A6,6,0,0,1,21,50.2L65.52,36.34a6,6,0,0,1,7.48,4l7.73,25.06A6,6,0,0,1,76.79,72.87Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><polygon points="75.27 47.3 19.28 64.71 17.14 57.76 73.12 40.35 75.27 47.3" fill="#6576ff"></polygon><path d="M30,77.65l-1.9-6.79a1,1,0,0,1,.69-1.23l4.59-1.3a1,1,0,0,1,1.23.7l1.9,6.78A1,1,0,0,1,35.84,77l-4.59,1.3A1,1,0,0,1,30,77.65Z" fill="#c4cefe"></path><path d="M41.23,74.48l-1.9-6.78A1,1,0,0,1,40,66.47l4.58-1.3a1,1,0,0,1,1.23.69l1.9,6.78A1,1,0,0,1,47,73.88l-4.58,1.29A1,1,0,0,1,41.23,74.48Z" fill="#c4cefe"></path><path d="M52.43,71.32l-1.9-6.79a1,1,0,0,1,.69-1.23L55.81,62A1,1,0,0,1,57,62.7l1.9,6.78a1,1,0,0,1-.69,1.23L53.66,72A1,1,0,0,1,52.43,71.32Z" fill="#c4cefe"></path><ellipse cx="55.46" cy="19.1" rx="16.04" ry="16.1" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></ellipse><ellipse cx="55.46" cy="19.1" rx="12.11" ry="12.16" fill="#e3e7fe"></ellipse><text transform="translate(50.7 23.72) scale(0.99 1)" font-size="16.12" fill="#6576ff" font-family="Nunito-Black, Nunito Black">$</text></svg></div>
                                
                                <h6 class="title">Nạp Tiền Đa Dạng</h6>
                                <p class="text-soft">Hỗ trợ nạp tiền qua thẻ cào và chuyển khoản ngân hàng nhanh chóng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="preview-icon-wrap mb-2" style="height: 6rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90"><rect x="5" y="10" width="70" height="60" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><rect x="15" y="20" width="70" height="60" rx="7" ry="7" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><path d="M47.4,52.58S37.23,62.76,31.63,56.16c-4.88-5.76-5.13-11.09-1.41-14.81s11.53-3.87,15.92,1.19,10,10.79,12.49,12.35,11.83,2.75,13.62-5.36-5.13-9.3-7.59-9.67c-.69-.1-2.27,1-4.39,2.29C54.93,45.42,47.4,52.58,47.4,52.58Z" fill="#e3e7fe" fill-rule="evenodd"></path><path d="M44.66,41.42a11,11,0,0,0-4.81-2.78,10.12,10.12,0,1,0,0,19.72,11,11,0,0,0,4.81-2.78q1.58-1.45,3.1-2.94l-.2-.19c-1,1-2.05,2-3.08,2.93a10.65,10.65,0,0,1-4.7,2.71,9.84,9.84,0,1,1,0-19.18,10.65,10.65,0,0,1,4.7,2.71c4.52,4.22,8.85,8.64,13.38,12.86A9.48,9.48,0,0,0,62,56.89a8.61,8.61,0,1,0,0-16.78,9.48,9.48,0,0,0-4.11,2.41c-1,.95-2,1.91-3,2.88l.2.19,3-2.87a9.3,9.3,0,0,1,4-2.34,8.34,8.34,0,1,1,0,16.24,9.3,9.3,0,0,1-4-2.34c-4.52-4.22-8.85-8.65-13.38-12.86Z" fill="#6576ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" fill-rule="evenodd"></path></svg></div>
                                
                                <h6 class="title">Xử Lý Nhanh</h6>
                                <p class="text-soft">Giao dịch được xử lý tự động, nhanh chóng và chính xác</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="preview-icon-wrap mb-2" style="height: 6rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90"><rect x="5" y="9" width="70" height="51.71" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><path d="M15,63.7V25.91a7,7,0,0,1,7-7H78a7,7,0,0,1,7,7V63.7a7,7,0,0,1-7,7H31L15,81Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M63,51.72v4.39a2.94,2.94,0,0,1-3,2.94h-.28A29.49,29.49,0,0,1,47,54.54,29.26,29.26,0,0,1,33.58,33.07a2.93,2.93,0,0,1,2.68-3.17l.27,0H41a3,3,0,0,1,3,3.27,3.75,3.75,0,0,0,.63,2.65,2.9,2.9,0,0,1-.27,3.8l-1.88,1.86a23.51,23.51,0,0,0,8.87,8.78l1.88-1.86a3,3,0,0,1,3.15-.65,19.64,19.64,0,0,0,4.13,1A2.93,2.93,0,0,1,63,51.72Z" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><text transform="translate(50.23 41.17) scale(1.01 1)" font-size="14.07" fill="#9cabff" font-family="Nunito-Bold, Nunito" font-weight="700" letter-spacing="-0.05em">24</text></svg></div>
                                
                                <h6 class="title">Hỗ Trợ 24/7</h6>
                                <p class="text-soft">Đội ngũ hỗ trợ chuyên nghiệp luôn sẵn sàng giải đáp thắc mắc</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="preview-icon-wrap mb-2" style="height: 6rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90"><path d="M29,74H11a7,7,0,0,1-7-7V17a7,7,0,0,1,7-7H61a7,7,0,0,1,7,7V30" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M11,11H61a6,6,0,0,1,6,6v4a0,0,0,0,1,0,0H5a0,0,0,0,1,0,0V17A6,6,0,0,1,11,11Z" fill="#e3e7fe"></path><circle cx="11" cy="16" r="2" fill="#6576ff"></circle><circle cx="17" cy="16" r="2" fill="#6576ff"></circle><circle cx="23" cy="16" r="2" fill="#6576ff"></circle><rect x="11" y="27" width="19" height="19" rx="1" ry="1" fill="#c4cefe"></rect><path d="M33.8,53.79c.33-.43.16-.79-.39-.79H12a1,1,0,0,0-1,1V64a1,1,0,0,0,1,1H31a1,1,0,0,0,1-1V59.19a10.81,10.81,0,0,1,.23-2Z" fill="#c4cefe"></path><line x1="36" y1="29" x2="60" y2="29" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="36" y1="34" x2="55" y2="34" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="36" y1="39" x2="50" y2="39" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="36" y1="44" x2="46" y2="44" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><rect x="4" y="21" width="64" height="2" fill="#6576ff"></rect><rect x="36" y="56" width="38" height="24" rx="5" ry="5" fill="#fff" stroke="#e3e7fe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><rect x="41" y="60" width="12" height="9" rx="1" ry="1" fill="#c4cefe"></rect><path d="M84.74,53.51,66.48,35.25a4.31,4.31,0,0,0-6.09,0l-9.13,9.13a4.31,4.31,0,0,0,0,6.09L69.52,68.73a4.31,4.31,0,0,0,6.09,0l9.13-9.13A4.31,4.31,0,0,0,84.74,53.51Zm-15-5.89L67,50.3a2.15,2.15,0,0,1-3,0l-4.76-4.76a2.16,2.16,0,0,1,0-3l2.67-2.67a2.16,2.16,0,0,1,3,0l4.76,4.76A2.15,2.15,0,0,1,69.72,47.62Z" fill="#6576ff"></path></svg></div>
                                
                                <h6 class="title">Phát Triển Bền Vững</h6>
                                <p class="text-soft">Luôn cập nhật tính năng mới và cải thiện trải nghiệm người dùng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="preview-icon-wrap mb-2" style="height: 6rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90"><rect x="7" y="10" width="76" height="50" rx="7" ry="7" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><rect x="32" y="69" width="28" height="7" rx="1.5" ry="1.5" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><line x1="40" y1="60" x2="40" y2="69" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="52" y1="60" x2="52" y2="69" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="42" y1="24" x2="70" y2="24" fill="#c4cefe" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="42" y1="30" x2="70" y2="30" fill="#c4cefe" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="42" y1="36" x2="70" y2="36" fill="#c4cefe" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><rect x="24" y="23" width="12" height="14" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><rect x="69" y="50" width="18" height="30" rx="3" ry="3" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><line x1="78.09" y1="75.56" x2="78.09" y2="75.56" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><rect x="3" y="46" width="24" height="34" rx="3" ry="3" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><line x1="14.69" y1="76.66" x2="14.69" y2="76.66" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><line x1="13.5" y1="49.5" x2="16.5" y2="49.5" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round"></line><line x1="3.5" y1="73.5" x2="26.5" y2="73.5" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round"></line></svg></div>
                                
                                <h6 class="title">Tương Thích Đa Thiết Bị</h6>
                                <p class="text-soft">Giao diện responsive, hoạt động tốt trên mọi thiết bị</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>