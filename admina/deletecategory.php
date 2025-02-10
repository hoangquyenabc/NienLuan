<?php
    require_once "connect.php";
    $id = $_GET['id'];
    $sql = "DELETE FROM loai_sp WHERE ID_L = $id";
    $query = mysqli_query($conn, $sql);
    header('location: category.php');
?>    