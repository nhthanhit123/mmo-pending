<?php

/*
    MÃ NGUỒN VIẾT BỞI NGUYỄN THÀNH - NIFY.VN
    Vui Lòng Không Xóa Để Tôn Trọng Tác Giả!
    Liên Hệ: 
     + Facebook: https://web.facebook.com/iamthanh.xDeveloper/
     + Zalo: 0764780360
*/

include('app/database.php');
include('app/router.php');

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on'){
    $redirect_url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header('Location: '.$redirect_url);
    exit();
}
?>
  