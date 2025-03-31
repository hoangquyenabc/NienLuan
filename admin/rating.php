<?php
    session_start();
    require('includes/header.php');
    require_once "connect.php";
 ?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Đánh giá
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
                    <li><a class="dropdown-item" href="editstaff.php">Thông tin</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../dangxuat.php">Đăng xuất</a></li>
                </ul>
            </div>
        <?php endif; ?>

</div>

    <div class="container-sp">
        <?php
    if (isset($_POST["tim"])) {
        $tim = trim($_POST["timkiem"]);
        if ($tim == "") {
            echo "<p style='color:red;'>Vui lòng nhập từ khóa tìm kiếm</p>";
        } else {
            $sql_tr = "SELECT d.*, k.*, s.* 
                        FROM danh_gia d 
                        JOIN khach_hang k ON d.ID_KH = k.ID_KH
                        JOIN san_pham s ON s.ID_SP = d.ID_SP
                        WHERE s.Ten_SP LIKE '%$tim%' 
                        ORDER BY s.Ten_SP;";
        }
    } else {
        $sql_tr = "SELECT d.*, k.*, s.* 
                    FROM danh_gia d 
                    JOIN khach_hang k ON d.ID_KH = k.ID_KH
                    JOIN san_pham s ON s.ID_SP = d.ID_SP;";
    }

    $result = mysqli_query($conn, $sql_tr);
?>


    
    <div class="search-bar">
        <form action="" method="POST" onsubmit="return validateForm()">
            <div class="search-bar">
                <input type="text" id="timkiem-dg" name="timkiem" placeholder="Tìm kiếm...">
                <button type="submit" class="tim" name="tim">Tìm</button>
            </div>
        </form>
    </div>
<div class="product-list">
    <div class="header">Danh sách đánh giá</div>
    <table style="margin-left: 1px;">
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Sản phẩm</th>
                <th>Nội dung</th>
                <th>Rating</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="productTableBody">
            <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Ho_KH']) . ' ' . htmlspecialchars($row['Ten_KH']); ?></td>
                    <td><?=$row['Ten_SP']?></td>
                    <td><?=$row['NoiDung']?></td>
                    <td><?=$row['Rating']?></td>
                    <td>
                        <a href="edit_rating.php?id=<?=$row['ID_SP']?>">
                            <button class="action-btn edit-btn" style="font-size: 16px;">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                        </a>
                        <a onclick="return confirm('Bạn có chắc muốn xóa thông tin này?');" href="delete_rating.php?id=<?=$row['ID_SP']?>">
                            <button class="action-btn delete-btn" style="font-size: 16px;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </a>
                    </td>
                </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='7'>Không tìm thấy thông tin.</td></tr>";
                }
            ?>
        </tbody>
    </table>

</div>
    <div style="display: flex; justify-content: flex-end;">
        <button id="exportExcelBtn" class="export-btn" 
            style="background-color: #338dbc; color: white; border: none; padding: 10px 15px; font-size: 16px; border-radius: 5px; cursor: pointer; display: flex; align-items: center; gap: 8px; margin-top:10px;">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </button>
    </div>
                
<script>
    function validateForm() {
        const input = document.getElementById("timkiem-dg").value.trim(); 
        if (input === "") {
            alert("Vui lòng nhập từ khóa trước khi tìm kiếm!");
            return false; 
        }
        return true; 
    }

    document.getElementById("exportExcelBtn").addEventListener("click", function() {
        window.location.href = "export_excel.php";
    });
    

</script>
