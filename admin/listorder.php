<?php
    session_start();
    require('includes/header.php');
    require_once "connect.php";
 ?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Đơn hàng
        </div>
        <?php if (isset($_SESSION['username'])): ?>
            <div class="dropdown">
                <a 
                    href="#" 
                    class="custom-dropdown-toggle" 
                    id="userDropdown" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false">
                    <?php echo "Xin chào, " . htmlspecialchars($_SESSION['username']) . " !"; ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="customer.php">Thông tin</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../dangxuat.php">Đăng xuất</a></li>
                </ul>
            </div>
        <?php endif; ?>

</div>
    <div class="container-sp" style="margin-top: 100px;">
        <?php
    if (isset($_POST["tim"])) {
        $tim = trim($_POST["timkiem"]);
        if ($tim == "") {
            echo "<p style='color:red;'>Vui lòng nhập từ khóa tìm kiếm</p>";
        } else {
            $sql_tr = "SELECT d.*, p.*, k.* 
                       FROM don_hang d
                       JOIN phuong_thuc_thanh_toan p ON p.ID_TT = d.ID_TT
                       JOIN khach_hang k ON k.ID_KH = d.ID_KH
                       WHERE k.Ten_KH LIKE '%$tim%' 
                       ORDER BY NgayTao";
        }
    } else {
        $sql_tr = "SELECT d.*, p.*, k.* 
                   FROM don_hang d
                   JOIN phuong_thuc_thanh_toan p ON p.ID_TT = d.ID_TT
                   JOIN khach_hang k ON k.ID_KH = d.ID_KH
                   ORDER BY NgayTao";
    }

    $result = mysqli_query($conn, $sql_tr);
?>

    
    <div class="search-bar">
        <form action="" method="POST" onsubmit="return validateForm()">
            <div class="search-bar">
                <input type="text" id="timkiem-dh" name="timkiem" placeholder="Tìm kiếm...">
                <button type="submit" class="tim" name="tim">Tìm</button>
            </div>
        </form>
    </div>
        <div class="product-list">
            <div class="header">Danh sách đơn hàng</div>
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
                        while ($row = mysqli_fetch_assoc($result)){
                    ?>  
                    <tr>
                        <td><?=$row['ID_DH']?></td>
                        <td><?php echo htmlspecialchars($row['Ho_KH']) . ' ' . htmlspecialchars($row['Ten_KH']); ?></td>
                        <td>
                            <?=$row['NgayTao']?>
                        </td>
                        <td>
                        <a href="editorder.php?id=<?=$row['ID_DH']?>">
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
                                    ?>"
                                <?php if ($row['TrangThai_DH'] === 'Đã hoàn thành' || $row['TrangThai_DH'] === 'Đã hủy') echo 'disabled'; ?>>
                                <i><?=$row['TrangThai_DH']?></i> 
                            </button>
                        </a>
                        </td>
                        <td><?= number_format($row['TongTien'], 0, '.', ',') ?> VNĐ</td>
                        
                        <!--  -->
                        <td><?=$row['Ten_TT']?></td>
                        
                        <td>
                        <a href="vieworder.php?id=<?=$row['ID_DH']?>">
                            <button class="action-btn view-bt" style="font-size: 16px; background-color: #4CAF50;">
                                <i class="fas fa-eye"></i> Chi tiết
                            </button>
                        </a>
                        
                        </td>                        
                    </tr>
                    <?php 
                    }  
                    ?>  
                </tbody>
            </table>
        </div>
    </div>
<script>
    function validateForm() {
        const input = document.getElementById("timkiem-dh").value.trim(); 
        if (input === "") {
            alert("Vui lòng nhập từ khóa trước khi tìm kiếm!");
            return false; 
        }
        return true; 
    }
</script>