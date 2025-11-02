<?php

/*
    MÃ NGUỒN VIẾT BỞI NGUYỄN THÀNH - CYBERLUX.VN
    Vui Lòng Không Xóa Để Tôn Trọng Tác Giả!
    Liên Hệ: 
     + Facebook: https://web.facebook.com/iamthanh.xDeveloper/
     + Zalo: 0764780360
*/

$routes = [
    '/' => 'public/client/index.php',
    '/auth/login' => 'public/client/auth/login.php',
    '/auth/register' => 'public/client/auth/register.php',
    '/auth/forgot' => 'public/client/auth/forgot.php',
    '/auth/reset' => 'public/client/auth/reset.php',
    '/deposit' => 'public/client/deposit.php',
    
    # Services
    '/kho-giao-dien' => 'public/client/website/kho-giao-dien.php',
    '/w-generate' => 'public/client/website/thanh-toan.php',
    
    # Manage 
    '/manage/web' => 'public/client/website/manage.php',
    '/manage/web-order' => 'public/client/website/order.php',
    
    # Ajaxs Client
    '/ajaxs/client/login' => 'backend/client/login.php',
    '/ajaxs/client/register' => 'backend/client/register.php',
    '/ajaxs/logout' => 'backend/logout.php',
    '/ajaxs/client/deposit' => 'backend/client/deposit.php',
    '/ajaxs/client/website/thanh-toan' => 'backend/client/website/thanh-toan.php',
    '/ajaxs/client/website/cancel-order' => 'backend/client/website/cancel-order.php',
    '/ajaxs/client/website/renew' => 'backend/client/website/renew.php'
];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$get = [];

$pathParts = explode('/', trim($path, '/'));
$basePath = '';
$foundRoute = false;

for($i = 0; $i < count($pathParts); $i++){
    if($i > 0){
        $basePath .= '/';
    }
    $basePath .= $pathParts[$i];
    
    if(array_key_exists('/' . $basePath, $routes)){
        $foundRoute = true;
        $routePath = '/' . $basePath;
        
        for($j = $i + 1; $j < count($pathParts); $j++){
            $get[] = $pathParts[$j];
        }
        
        break;
    }
}

if($foundRoute){
    # Xử Lí Ajaxs
    if(strpos($routePath, '/ajaxs/') === 0 || strpos($routePath, '/cron/') === 0){   
        if(strpos($routePath, '/ajaxs/admin/') === 0){
            # Check Điều Kiện Ajax Admin
        } else {
           header('Content-Type: application/json; charset=utf-8');
            if(!csrf_verify()){
                echo json_encode(['status' => false, 'message' => 'CSRF token không hợp lệ hoặc đã hết hạn!']);
                exit;
            }
            
           include $routes[$routePath]; 
        }
    } else 
    
    # Xử Lí Logic Admin 
    if(strpos($routePath, '/admin/') === 0){   
        # Check Điều Kiện Admin
    } else {
        include('public/client/layouts/header.php');
        include $routes[$routePath];
        include('public/client/layouts/footer.php');
    }
} else {
    include('public/404.php');
}
?>
