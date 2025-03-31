<?php
require_once "connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id']; 

    $sql = "UPDATE san_pham SET TrangThai_SP = 'inactive' WHERE ID_SP = $id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Sản phẩm đã được xoá thành công!'); window.location.href='products.php';</script>";
    } else {
       
    }
    mysqli_close($conn);
} 
?>
