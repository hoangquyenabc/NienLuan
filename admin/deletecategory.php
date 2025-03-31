<?php
require_once "connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id']; 

    $sql = "UPDATE loai_sp SET TrangThai_L = 'inactive' WHERE ID_L = $id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Loại sản phẩm đã được xoá thành công!'); window.location.href='category.php';</script>";
    } else {
       
    }
    mysqli_close($conn);
} 
?>
