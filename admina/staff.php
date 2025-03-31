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
                    <li><a class="dropdown-item" href="editstaff.php">Thông tin</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../dangxuat.php">Đăng xuất</a></li>
                </ul>
            </div>
        <?php endif; ?>

</div>

    <div class="container-sp">
        <!-- Khung tìm kiếm sản phẩm -->
        <?php
    if (isset($_POST["tim"])) {
        $tim = trim($_POST["timkiem"]); // Loại bỏ khoảng trắng
        if ($tim == "") {
            echo "<p style='color:red;'>Vui lòng nhập từ khóa tìm kiếm</p>";
            // Truy vấn tất cả các đơn hàng nếu không có tìm kiếm
            $sql_tr = "SELECT * FROM nhan_vien";
        } else {
            // Truy vấn theo từ khóa
            $sql_tr = "SELECT * FROM nhan_vien
                        WHERE Ten_NV LIKE '%$tim%' 
                       ORDER BY ID_NV";
        }
    } else {
        // Nếu không có tìm kiếm, hiển thị tất cả đơn hàng
        $sql_tr = "SELECT * FROM nhan_vien";
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
        <!-- Khung danh sách sản phẩm -->
        <div class="product-list">
            <div class="header">Danh sách nhân viên</div>
            <table  style="margin-left: 1px;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ nhân viên</th>
                        <th>Tên nhân viên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ngày tuyển</th>
                        <th>Vai trò</th>
                        
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <?php
                        while ($row = mysqli_fetch_assoc($result)){
                    ?>  
                    <tr>
                        <td><?=$row['ID_NV']?></td>
                        <td><?=$row['Ho_NV']?></td>
                        <td name="tdten"><?=$row['Ten_NV']?></td>                       
                        <td><?=$row['Email_NV']?></td>
                        <td><?=$row['SDT_NV']?></td>
                        <td><?=$row['DiaChi_NV']?></td>
                        <td><?=$row['NgayTuyen']?></td>
                        <td><?=$row['VaiTro']?></td>
                        
                                         
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
