<?php
    session_start();
    require('includes/header.php');
    require_once "connect.php";
 ?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Nhân viên
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
            $sql_tr = "SELECT * FROM nhan_vien 
                       ;";
            $result = mysqli_query($conn, $sql_tr);
        ?>
        <?php
            if (!isset($_SESSION['id_nv'])) {
            die("Bạn cần đăng nhập để xem thông tin.");
            }
            $current_user_id = $_SESSION['id_nv'];
            $sql = "SELECT * FROM nhan_vien WHERE ID_NV = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $current_user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {

        ?>
        <div class="product-list">
            <table  style="margin-left: 1px;">
                <thead>
                    <tr>
                        <th>Họ</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ngày tuyển</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                        
                    </tr>
                </thead>
                <tbody id="productTableBody">
                     
                    <tr>
                        
                        <td><?=$row['Ho_NV']?></td>
                        <td name="tdten"><?=$row['Ten_NV']?></td>                       
                        <td><?=$row['Email_NV']?></td>
                        <td><?=$row['SDT_NV']?></td>
                        <td><?=$row['DiaChi_NV']?></td>
                        <td><?=$row['NgayTuyen']?></td>
                        <td><?=$row['VaiTro']?></td>
                        
                        <td>
                        <a href="editadmin.php?id=<?=$row['ID_NV']?>">
                            <button class="action-btn edit-btn" style="font-size: 16px;">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                        </a>
                        <a onclick="return confirm('Bạn có chắc muốn xóa thông tin này?');" href="delete_staff.php?id=<?=$row['ID_NV']?>">
                            <button class="action-btn delete-btn" style="font-size: 16px;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </a>
                        </td>                        
                    </tr>
                      
                </tbody>
            </table>
        </div>
        <?php
            } else {
                echo "Không tìm thấy thông tin.";
            }
        ?>
    </div>
</body>
</html>
