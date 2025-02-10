<?php 
    session_start();
    require('includes/header.php');
    require_once "connect.php";
    
?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Thêm mới nhân viên
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

<div class="container-editproduct">
<?php
    
    $id_nv = $_GET['id'];
    $sql_up = "SELECT * FROM nhan_vien WHERE ID_NV = $id_nv";
    $query_up = mysqli_query($conn, $sql_up);
    $row_up = mysqli_fetch_assoc($query_up);
    if (isset($_POST['addsp'])) {
        $name = $_POST['name'];
        $lname = $_POST['lname'];
        $email = $_POST['length'];
        $address = $_POST['material'];
        $role = $_POST['rule'];
        $phone = $_POST['phone'];
        $password = $_POST['pass'];
        $confirm_password = $_POST['pass1'];

        $errors = [];

        // Kiểm tra mật khẩu
        if ($password !== $confirm_password) {
            $errors[] = "Mật khẩu không khớp.";
        }

        // Kiểm tra số điện thoại
        if (!preg_match('/^\d{10}$/', $phone)) {
            $errors[] = "Số điện thoại phải là số và có đúng 10 chữ số.";
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ.";
        }

        // Nếu không có lỗi, thêm vào cơ sở dữ liệu
        if (empty($errors)) {
            $sql = "UPDATE nhan_vien 
                    SET Ho_NV = '$name', 
                        Ten_NV = '$lname', 
                        VaiTro = '$role', 
                        Email_NV = '$email', 
                        SDT_NV = '$phone', 
                        DiaChi_NV = '$address', 
                        MatKhau_NV = '$password', 
                        NgayTuyen = NOW() 
                    WHERE ID_NV = '$id_nv'";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>
                            alert('Cập nhật nhân viên $name thành công!');
                            window.location.href = 'editstaff.php';  // Chuyển hướng đến trang category.php
                          </script>";
                
            } else {
                echo "<p style='color: red;'>Có lỗi xảy ra: " . mysqli_error($conn) . "</p>";
            }
        } else {
            // Hiển thị lỗi
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
        }
    }
?>

<h2 style="text-align: center; margin: 20px 0; font-size: 34px; color:#e91e63; margin-bottom: 20px;">Chỉnh sửa thông tin</h2>
<form method="POST" enctype="multipart/form-data">
    <!-- Tên và họ -->
    <div class="form-row">
        <div class="form-group">
            <label for="name">Họ nhân viên</label>
            <input type="text" id="name" name="name" placeholder="Nhập họ" required value="<?php echo $row_up['Ho_NV']; ?>">
        </div>
        <div class="form-group">
            <label for="lname">Tên nhân viên</label>
            <input type="text" id="lname" name="lname" placeholder="Nhập tên" required value="<?php echo $row_up['Ten_NV']; ?>">
        </div>
    </div>
    <!-- Email và địa chỉ -->
    <div class="form-row">
        <div class="form-group">
            <label for="length">Email</label>
            <input type="text" id="length" name="length" placeholder="Nhập email" required value="<?php echo $row_up['Email_NV']; ?>">
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" required value="<?php echo $row_up['SDT_NV']; ?>">
        </div>
        
    </div>
    <!-- Vai trò và số điện thoại -->
    <div class="form-row">
    <!-- <div class="form-group">
        <label for="rule">Vai trò</label>
        <select id="rule" name="rule" required>
            <option value="">Chọn vai trò</option>
            <option value="Admin" <?php echo ($row_up['VaiTro'] === 'Admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="Staff" <?php echo ($row_up['VaiTro'] === 'Staff') ? 'selected' : ''; ?>>Staff</option>
        </select>
    </div> -->
    <div class="form-group">
            <label for="material">Địa chỉ</label>
            <input type="text" id="material" name="material" placeholder="Nhập địa chỉ" required value="<?php echo $row_up['DiaChi_NV']; ?>">
        </div>
        
    </div>
    <!-- Mật khẩu và xác nhận mật khẩu -->
    <div class="form-row">
        <div class="form-group">
            <label for="pass">Mật khẩu</label>
            <input type="password" id="pass" name="pass" placeholder="Nhập mật khẩu" required value="<?php echo $row_up['MatKhau_NV']; ?>">
        </div>
        <div class="form-group">
            <label for="pass1">Nhập lại mật khẩu</label>
            <input type="password" id="pass1" name="pass1" placeholder="Nhập lại mật khẩu" required value="<?php echo $row_up['MatKhau_NV']; ?>">
        </div>
    </div>
    <!-- Nút thêm -->
    <button type="submit" class="submit-btn" name="addsp">Cập nhật</button>
</form>
</div>



<script>
       
        
        function Delete(name){
            const confirmDelete = confirm(`Bạn có chắc muốn xóa sản phẩm: ${name}?`);
            if (confirmDelete) {
                alert(`Sản phẩm "${name}" đã được xóa.`);

        
                setTimeout(function() {
                    window.location.href = 'editstaff.php'; 
                }, 1000); 
            }
        }
    </script>

</body>
</html>
