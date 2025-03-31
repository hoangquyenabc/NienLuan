<?php 
   
    require('header.php'); 
    require_once "connect.php";
  
?>


<div class="container-sp" style="max-width: 900px; margin-top: 50px; margin-left: 400px;">
<?php
$id_nv = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_nv <= 0) {
    die('ID khách hàng không hợp lệ.');
}

$sql_up = "SELECT d.*, h.*, k.* 
           FROM dia_chi_giao_hang d
           JOIN don_hang h ON d.ID_DH = h.ID_DH
           JOIN khach_hang k ON h.ID_KH = k.ID_KH
           WHERE k.ID_KH = $id_nv";
$query_up = mysqli_query($conn, $sql_up);
$row_up = mysqli_fetch_assoc($query_up);

if (isset($_POST['addsp'])) {
    $name = $_POST['name'];
    $lname = $_POST['lname'];
    $email = $_POST['length'];
    $address = $_POST['material'];
    $phone = $_POST['phone'];
    $password = $_POST['pass'];
    $confirm_password = $_POST['pass1'];
    $material1 = $_POST['material1'];
    $material2 = $_POST['material2'];
    $material3 = $_POST['material3'];

    $errors = [];

    if ($password !== $confirm_password) {
        $errors['pass1'] = "Mật khẩu không khớp.";
    }

    if (!preg_match('/^\d{10}$/', $phone)) {
        $errors['phone'] = "Số điện thoại phải là số và có đúng 10 chữ số.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['length'] = "Email không hợp lệ.";
    }

    if (empty($errors)) {
        $sql = "UPDATE khach_hang 
                SET Ho_KH = '$name', 
                    Ten_KH = '$lname', 
                    Email_KH = '$email',     
                    MatKhau_KH = '$password' 
                WHERE ID_KH = '$id_nv'";

        $sql_str = "UPDATE dia_chi_giao_hang
                    SET SoNha = '$address', 
                        XaPhuong = '$material1', 
                        QuanHuyen = '$material2', 
                        TinhTP = '$material3', 
                        sdt = '$phone'
                    WHERE ID_DH = (SELECT ID_DH FROM don_hang WHERE ID_KH = '$id_nv' LIMIT 1);";

        if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql_str)) {
            echo "<script>
                    alert('Cập nhật khách hàng $name thành công!');
                    window.location.href = 'customer.php';
                  </script>";
        } else {
            // echo "<p style='color: red;'>Có lỗi xảy ra khi cập nhật: " . mysqli_error($conn) . "</p>";
        }
    } else {
        foreach ($errors as $error) {
            // echo "<p style='color: red;'>$error</p>";
        }
    }
}
?>


<h2 style="text-align: center; margin: 20px 0; font-size: 34px; color:#e91e63; margin-bottom: 20px; color: #7fad39">Chỉnh sửa thông tin</h2>
<form method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group">
            <label for="name">Họ khách hàng</label>
            <input type="text" id="name" name="name" placeholder="Nhập họ" required value="<?php echo $row_up['Ho_KH']; ?>">
        </div>
        <div class="form-group">
            <label for="lname">Tên khách hàng</label>
            <input type="text" id="lname" name="lname" placeholder="Nhập tên" required value="<?php echo $row_up['Ten_KH']; ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="length">Email</label>
            <input type="text" id="length" name="length" placeholder="Nhập email" required value="<?php echo $row_up['Email_KH']; ?>">
            <?php
                if (isset($errors['length'])) {
                    echo '<p style="color: red; font-size: 14px;  margin-top: 5px;">' . $errors['length'] . '</p>';
                }
            ?>
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" required value="<?php echo $row_up['sdt']; ?>">
            <?php
                if (isset($errors['phone'])) {
                    echo '<p style="color: red; font-size: 14px;  margin-top: 5px;">' . $errors['phone'] . '</p>';
                }
            ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="material">Số nhà</label>
            <input type="text" id="material" name="material" placeholder="Nhập số nhà" required value="<?php echo $row_up['SoNha']; ?>">
        </div>
        <div class="form-group">
            <label for="material">Xã/phường</label>
            <input type="text" id="material1" name="material1" placeholder="Nhập xã phường" required value="<?php echo $row_up['XaPhuong']; ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="material">Quận/huyện</label>
            <input type="text" id="material2" name="material2" placeholder="Nhập quận huyện" required value="<?php echo $row_up['QuanHuyen']; ?>">
        </div>
        <div class="form-group">
            <label for="material">Tỉnh/thành phố</label>
            <input type="text" id="material3" name="material3" placeholder="Nhập tỉnh thành phố" required value="<?php echo $row_up['TinhTP']; ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="pass">Mật khẩu</label>
            <input type="password" id="pass" name="pass" placeholder="Nhập mật khẩu" required value="<?php echo $row_up['MatKhau_KH']; ?>">
        </div>
        <div class="form-group">
            <label for="pass1">Nhập lại mật khẩu</label>
            <input type="password" id="pass1" name="pass1" placeholder="Nhập lại mật khẩu" required value="<?php echo $row_up['MatKhau_KH']; ?>">
            <?php
                if (isset($errors['pass1'])) {
                    echo '<p style="color: red; font-size: 14px;  margin-top: 5px;">' . $errors['pass1'] . '</p>';
                }
            ?>
        </div>
    </div>
    <button type="submit" class="submit-btn" name="addsp" style="background-color: #7fad39">Cập nhật</button>
</form>
</div>
<?php 
    require('footer.php'); 

?>  
