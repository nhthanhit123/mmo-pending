<?php
setcookie('remember_token', '', time() - 3600, '/', '', false, true);
session_destroy();
echo json_encode(['status' => true, 'message' => 'Đăng xuất thành công!']);
