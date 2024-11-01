<?php
// Bắt đầu hoặc tiếp tục phiên
session_start();

// Xóa tất cả biến phiên
session_unset();

// Hủy phiên làm việc
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit();
?>
