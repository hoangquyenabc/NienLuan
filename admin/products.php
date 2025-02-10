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
                        <th>Loại sản phẩm</th>
                        
                        <th>Hành động</th>
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
                        <form method="post" action="update.php" 
                            onsubmit="return tinhSoLuongMoi(this)"
                            style="display: flex; gap: 5px; align-items: center;">
                            
                            <input type="hidden" name="id" value="<?=$row['ID_SP']?>">

                            <!-- Ô hiển thị số lượng hiện tại (chỉ đọc) -->
                            <input type="number" id="SoLuongHienTai_<?=$row['ID_SP']?>" value="<?=$row['SoLuongKho']?>" readonly 
                                style="width: 50px; padding: 3px; text-align: center; border: 1px solid #ccc; border-radius: 3px;">

                            <!-- Ô nhập số lượng muốn thêm -->
                            <input type="number" id="SoLuongThem_<?=$row['ID_SP']?>" min="0" value="0" required
                                style="width: 50px; padding: 3px; text-align: center; border: 1px solid #ccc; border-radius: 3px;">

                            <!-- Ô ẩn lưu tổng số lượng mới -->
                            <input type="hidden" name="SoLuongKho" id="SoLuongMoi_<?=$row['ID_SP']?>">

                            <button type="submit" 
                                style="padding: 5px 10px; font-size: 12px; background-color: #4CAF50; color: white; 
                                    border: none; cursor: pointer; border-radius: 3px;">
                                Nhập thêm
                            </button>
                        </form>


                        </td>
                        <td><?=$row['Ten_L']?></td>
                        
                        <td>
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
                        </td>                        
                    </tr>
                    <?php 
                    }  
                    ?>  
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
