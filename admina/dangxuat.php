<?php
session_start();
session_unset(); // Xóa toàn bộ session
session_destroy(); // Hủy session
header("Location: dangnhap.php"); // Chuyển hướng về trang đăng nhập
exit();
?>
