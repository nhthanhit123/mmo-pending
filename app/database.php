<?php
if(session_status() === PHP_SESSION_NONE) session_start();

define("DBHOST", "LOCALHOST");
define("DBNAME", "arownmqdn9q_dbdemo");
define("DBUSER", "arownmqdn9q_dbdemo");
define("DBPASS", "arownmqdn9q_dbdemo");

# Cài Đặt Website
# Thêm Các Config Tĩnh Nếu Muốn 

date_default_timezone_set('Asia/Ho_Chi_Minh'); 
// header_remove('Set-Cookie');
include('ini.php');
?>