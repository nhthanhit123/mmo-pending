<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
    <div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
    <div class="nk-block-head-content">
    <h3 class="nk-block-title page-title"> Kho Giao Diện </h3>
</div>
<div class="nk-block-head-content">
    <div class="toggle-wrap nk-block-tools-toggle">
    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
    <em class="icon ni ni-more-v">
    
</em>
</a>
<div class="toggle-expand-content" data-content="pageMenu">
    <ul class="nk-block-tools g-3">
    <li>
    <div class="form-control-wrap">
    <div class="form-icon form-icon-right">
    <em class="icon ni ni-search">
    
</em>
</div>
<input type="text" class="form-control" id="search-interface" placeholder="Tìm kiếm giao diện...">
</div>
</li>
<li>
    <div class="drodown">
    <a href="#" class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white" data-bs-toggle="dropdown" aria-expanded="false">Danh mục</a>
<div class="dropdown-menu dropdown-menu-end" style="">
    <ul class="link-list-opt no-bdr">
    <li>
        <a href="#" onclick="filterByCategory('all')">
            <span>Tất cả</span>
        </a>
    </li>
        <?php
        $getCategories = $nify->query("SELECT * FROM `website_categories` WHERE `status` = 'active' ORDER BY `sort_order` ASC");
        if($getCategories->num_rows > 0):
            while($category = $getCategories->fetch_assoc()):
        ?>
        <li>
            <a href="#" onclick="filterByCategory(<?= $category['id'] ?>)">
                <span><?= $category['name'] ?></span>
            </a>
        </li>
        <?php
            endwhile;
        endif;
        ?>
    </ul>
</div>
</div>
</li>
</ul>
</div>
</div>
</div>
</div>
</div>

<!-- Hiển thị danh mục -->
<div class="nk-block">
    <div class="row g-gs mb-4">
        <?php
        $getCategories = $nify->query("SELECT * FROM `website_categories` WHERE `status` = 'active' ORDER BY `sort_order` ASC");
        if($getCategories->num_rows > 0):
            while($category = $getCategories->fetch_assoc()):
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card card-bordered category-card" onclick="filterByCategory(<?= $category['id'] ?>)" style="cursor: pointer;">
                <div class="card-inner">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="user-avatar sq bg-primary">
                                <?php if($category['image']): ?>
                                    <img src="<?= $category['image'] ?>" alt="<?= $category['name'] ?>">
                                <?php else: ?>
                                    <span><?= substr($category['name'], 0, 2) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title"><?= $category['name'] ?></h5>
                            <p class="card-text text-muted small"><?= $category['description'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            endwhile;
        endif;
        ?>
    </div>
</div>

<div class="nk-block">
    <div class="row g-gs" id="interfaces-container">
        <?php
        $getInterfaces = $nify->query("SELECT wi.*, wc.name as category_name FROM `website_interfaces` wi 
                                     LEFT JOIN `website_categories` wc ON wi.category_id = wc.id 
                                     WHERE wi.`status` = 'active' ORDER BY wi.`is_featured` DESC, wi.`created_at` DESC");
        if($getInterfaces->num_rows > 0):
            while($interface = $getInterfaces->fetch_assoc()):
        ?>
        <div class="col-xxl-4 col-lg-4 col-sm-6 interface-item" data-category="<?= $interface['category_id'] ?>">
            <div class="card product-card">
                <div class="product-thumb">
                    <a href="/w-generate/<?= $interface['id'] ?>">
                        <img class="card-img-top" src="<?= $interface['image'] ?: 'https://via.placeholder.com/400x300' ?>" alt="<?= $interface['name'] ?>" style="height: 15rem;">
                    </a>

                    <ul class="product-badges">
                        <?php if($interface['is_featured']): ?>
                        <li>
                            <span class="badge bg-success">Nổi bật</span>
                        </li>
                        <?php endif; ?>
                        <?php if($interface['sale_price'] && $interface['sale_price'] < $interface['price']): ?>
                        <li>
                            <span class="badge bg-danger">Giảm giá</span>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <ul class="product-actions">
                        <div class="bg-primary" style="display: flex;">
                            <li>
                                <a href="/w-generate/<?= $interface['id'] ?>">
                                    <em class="icon ni ni-cart text-white"></em>
                                </a>
                            </li>
                            <li>
                                <a href="<?= $interface['demo_url'] ?>" target="_blank">
                                    <em class="icon ni ni-eye text-white"></em>
                                </a>
                            </li>
                        </div>
                    </ul>
                </div>

                <div class="card-inner text-center">
                    <ul class="product-tags">
                        <li>
                            <a href="javascript:void(0)"><em class="ni ni-view-x2-alt mr-2"></em> <?= $interface['category_name'] ?: $interface['category'] ?></a>
                        </li>
                    </ul>

                    <h5 class="product-title">
                        <a href="/w-generate/<?= $interface['id'] ?>"><em class="ni ni-bag mr-2"></em> <?= strtoupper($interface['name']) ?></a>
                    </h5>

                    <div class="product-price text-primary h5">
                        <?php if($interface['sale_price'] && $interface['sale_price'] < $interface['price']): ?>
                            <small class="text-muted del fs-13px"><?= number_format($interface['price'], 0, ',', '.') ?>đ</small>
                            <?= number_format($interface['sale_price'], 0, ',', '.') ?>đ
                        <?php else: ?>
                            <?= number_format($interface['price'], 0, ',', '.') ?>đ
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
            endwhile;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <em class="icon ni ni-alert-circle"></em>
                Chưa có giao diện nào trong kho.
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterByCategory(categoryId) {
    const items = document.querySelectorAll('.interface-item');
    items.forEach(item => {
        if (categoryId === 'all' || item.dataset.category == categoryId) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

document.getElementById('search-interface').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.interface-item');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>