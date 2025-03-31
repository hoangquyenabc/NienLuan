<?php
// $_SESSION = [];
session_unset();
session_destroy();
header("Location: dangnhap.php"); 
exit();
?>
