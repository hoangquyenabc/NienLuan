<?php
    require_once "connect.php";
    $id = $_GET['id'];
    $sql = "DELETE FROM danh_gia WHERE ID_SP = $id";
    $query = mysqli_query($conn, $sql);
    header('location: rating.php');
?>    