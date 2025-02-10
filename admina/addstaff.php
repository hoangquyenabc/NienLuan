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
                    <li><a class="dropdown-item" href="customer.php">Thông tin</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../dangxuat.php">Đăng xuất</a></li>
                </ul>
            </div>
        <?php endif; ?>

</div>

<div class="container-editproduct">
<?php
    

    // Xử lý form khi gửi
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
            $sql = "INSERT INTO nhan_vien (Ho_NV, Ten_NV, VaiTro, Email_NV, SDT_NV, DiaChi_NV, MatKhau_NV, NgayTuyen) 
                    VALUES ('$name', '$lname', '$role', '$email', '$phone', '$address', '$password', NOW())";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>
                            alert('Thêm mới nhân viên $name thành công!');
                            window.location.href = 'staff.php';  // Chuyển hướng đến trang category.php
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

<h2 style="text-align: center; margin: 20px 0; font-size: 34px; color:#e91e63; margin-bottom: 20px;">Thêm nhân viên mới</h2>
<form method="POST" enctype="multipart/form-data">
    <!-- Tên và họ -->
    <div class="form-row">
        <div class="form-group">
            <label for="name">Họ nhân viên</label>
            <input type="text" id="name" name="name" placeholder="Nhập họ" required>
        </div>
        <div class="form-group">
            <label for="lname">Tên nhân viên</label>
            <input type="text" id="lname" name="lname" placeholder="Nhập tên" required>
        </div>
    </div>
    <!-- Email và địa chỉ -->
    <div class="form-row">
        <div class="form-group">
            <label for="length">Email</label>
            <input type="text" id="length" name="length" placeholder="Nhập email" required>
        </div>
        <div class="form-group">
            <label for="material">Địa chỉ</label>
            <input type="text" id="material" name="material" placeholder="Nhập địa chỉ" required>
        </div>
    </div>
    <!-- Vai trò và số điện thoại -->
    <div class="form-row">
        <div class="form-group">
            <label for="rule">Vai trò</label>
            <select id="rule" name="rule" required>
                <option value="">Chọn vai trò</option>
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
            </select>
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
        </div>
    </div>
    <!-- Mật khẩu và xác nhận mật khẩu -->
    <div class="form-row">
        <div class="form-group">
            <label for="pass">Mật khẩu</label>
            <input type="password" id="pass" name="pass" placeholder="Nhập mật khẩu" required>
        </div>
        <div class="form-group">
            <label for="pass1">Nhập lại mật khẩu</label>
            <input type="password" id="pass1" name="pass1" placeholder="Nhập lại mật khẩu" required>
        </div>
    </div>
    <!-- Nút thêm -->
    <button type="submit" class="submit-btn" name="addsp">Thêm</button>
</form>
</div>



<script>
       
        
        function Delete(name){
            const confirmDelete = confirm(`Bạn có chắc muốn xóa sản phẩm: ${name}?`);
            if (confirmDelete) {
                alert(`Sản phẩm "${name}" đã được xóa.`);

        
                setTimeout(function() {
                    window.location.href = 'products.php'; 
                }, 1000); 
            }
        }
    </script>

</body>
</html>
