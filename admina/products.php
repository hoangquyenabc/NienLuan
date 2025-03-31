<?php
    session_start();
    require('includes/header.php');
    require_once "connect.php";
 ?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Sản phẩm
        </div>

        

        <?php if (isset($_SESSION['username'])): ?>
            <div class="dropdown">
                <!-- Tên người dùng -->
                <a 
                    href="#" 
                    class="custom-dropdown-toggle" 
                    id="userDropdown" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false">
                    <?php echo "Xin chào, " . htmlspecialchars($_SESSION['username']) . " !"; ?>
                </a>

                <!-- Menu Dropdown -->
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="customer.php">Thông tin</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../dangxuat.php">Đăng xuất</a></li>
                </ul>
            </div>
        <?php endif; ?>

</div>

    <div class="container-sp" style="margin-top: 100px;">
        <!-- Khung tìm kiếm sản phẩm -->
        <div class="search-bar">
            <form action="search-sp.php" method="POST">
                <div class="search-bar">
                    <input type="text" id="searchInput" name ="timkiem" placeholder="Tìm kiếm sản phẩm...">
                    <button type="submit" class="tim" name="tim" >Tìm</button>
                </div>
            </form>
        </div>
        <?php
            $sql_tr = "SELECT sp.*, bg.*, l.*, t.*
                        FROM san_pham sp
                        JOIN (
                            SELECT ID_SP, Gia, NgayGio
                            FROM co_gia bg1
                            WHERE NgayGio = (
                                SELECT MAX(NgayGio)
                                FROM co_gia bg2
                                WHERE bg1.ID_SP = bg2.ID_SP
                            )
                        ) bg ON sp.ID_SP = bg.ID_SP
                        JOIN loai_sp l ON l.ID_L = sp.ID_L
                        JOIN thuong_hieu t ON t.ID_TH = sp.ID_TH
                        ORDER BY sp.ID_SP
                       ;";
            $result = mysqli_query($conn, $sql_tr);
        ?>
        <!-- Khung danh sách sản phẩm -->
        <div class="product-list"  >
            <div class="header">Danh sách sản phẩm</div>
            <table  style="margin-left: 1px;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Giá bán</th>
                        <th>Số lượng kho</th>
                        <th>Thương hiệu</th>
                        <th>Loại sản phẩm</th>
                        
                        <!-- <th>Hành động</th> -->
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <?php
                        while ($row = mysqli_fetch_assoc($result)){
                    ?>  
                    <tr>
                        <td><?=$row['ID_SP']?></td>
                        <td name="tdten"><?=$row['Ten_SP']?></td>
                        <td>
                            <?php
                                $file = $row["HinhAnh"];
                                $avatar_url = "../images/" . $file;
                                echo "<img src='{$avatar_url}' style='width: 50px; height: auto;'>";
                            ?> 
                        </td>
                        <td><?= number_format($row['Gia'], 0, '.', ',') ?> VNĐ</td>
                        <td>
                            <?=$row['SoLuongKho']?>
                        </td>
                        <td><?=$row['Ten_TH']?></td>
                        <td><?=$row['Ten_L']?></td>
                        
                        <!-- <td>
                        <a href="editproduct.php?id=<?=$row['ID_SP']?>">
                            <button class="action-btn edit-btn" style="font-size: 16px;">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                        </a>
                        
                        <a onclick="return Delete('<?php echo $row['Ten_SP'];?>')" href="deleteproduct.php?id=<?=$row['ID_SP']?>">
                            <button class="action-btn delete-btn" style="font-size: 16px;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </a>
                        </td>                         -->
                    </tr>
                    <?php 
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
    </div>
    <script>
        document.getElementById("exportExcelBtn").addEventListener("click", function() {
            window.location.href = "export_excel_sp.php";
        });
    
    </script>

</body>
</html>
