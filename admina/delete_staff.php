<?php
    require_once "connect.php";
    $id = $_GET['id'];
    $sql = "DELETE FROM nhan_vien WHERE ID_NV = $id";
    $query = mysqli_query($conn, $sql);
    header('location: editstaff.php');
?>    