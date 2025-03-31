<?php 
    require('header.php'); 
    require_once "connect.php";

?>   

<?php
    if (!isset($_SESSION['ID_KH'])) {
        echo "<p>Vui lòng <a href='login.php'>đăng nhập</a> để xem đơn hàng của bạn.</p>";
        exit;
    }
    $id_kh = intval($_SESSION['ID_KH']);
    $sql_tr = "SELECT d.*, p.Ten_TT, k.Ten_KH, k.Ho_KH 
            FROM don_hang d
            JOIN phuong_thuc_thanh_toan p ON p.ID_TT = d.ID_TT
            JOIN khach_hang k ON k.ID_KH = d.ID_KH
            WHERE d.ID_KH = $id_kh
            ORDER BY d.NgayTao;";
    $result = mysqli_query($conn, $sql_tr);
    
    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
?>

<div class="container-sp" style="margin-top: 50px; margin-left: 250px;">
    <div class="product-list">
        <div class="header" style="color: #7fad39">Danh sách đơn hàng của bạn</div>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    $stt = 1;
                    while ($row = mysqli_fetch_assoc($result)) { 
                        ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td><?php echo htmlspecialchars($row['Ho_KH']) . ' ' . htmlspecialchars($row['Ten_KH']); ?></td>
                            <td><?= htmlspecialchars($row['NgayTao']) ?></td>
                            <td>
                                <button class="action-btn view-btn" 
                                    style="font-size: 16px; background-color: 
                                        <?php 
                                            if ($row['TrangThai_DH'] === 'Đang chờ xử lý') {
                                                echo '#c8d7e1'; 
                                            } elseif ($row['TrangThai_DH'] === 'Đã hoàn thành') {
                                                echo '#c6e1c6'; 
                                            } elseif ($row['TrangThai_DH'] === 'Đang giao hàng') {
                                                echo '#607d8b'; 
                                            } elseif ($row['TrangThai_DH'] === 'Đã thanh toán') {
                                                 echo '#ffc107';
                                            } else {
                                                echo '#dc3545'; 
                                            }
                                        ?>">
                                    <i><?= htmlspecialchars($row['TrangThai_DH']) ?></i> 
                                </button>
                            </td>
                            <td><?= number_format($row['TongTien'], 0, '.', ',') ?> VNĐ</td>
                            <td><?= htmlspecialchars($row['Ten_TT']) ?></td>
                            <td>
                                <a href="viewcustomer.php?order_id=<?= $row['ID_DH'] ?>">
                                    <button class="action-btn view-bt" style="font-size: 16px; background-color: #4CAF50;">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </button>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center;'>Không có đơn hàng nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<?php 
    require('footer.php'); 

?>  