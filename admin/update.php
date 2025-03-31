<?php
session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; 
    $soLuong = $_POST['SoLuongKho']; 

    $sql = "UPDATE san_pham SET SoLuongKho = ? WHERE ID_SP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $soLuong, $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Cập nhật thành công!');
                window.location.href = 'products.php'; 
              </script>";
    } else {
        echo "<script>
                alert('Lỗi cập nhật: " . $stmt->error . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
