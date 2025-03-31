<?php 
    require_once "connect.php";
    
    $err = []; 
    if( isset($_POST['dangky'])){
        $lname = $_POST['lname'];
        $fname = $_POST['fname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repass = $_POST['repass'];

        if (!preg_match("/^[\p{L} ]+$/u", $lname)) {
            $err['lname'] = 'Tên người dùng chỉ được chứa chữ cái và khoảng trắng!';
        }
        
        if (!preg_match("/^[\p{L} ]+$/u", $fname)) {
            $err['fname'] = 'Tên người dùng chỉ được chứa chữ cái và khoảng trắng!';
        }

        if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/", $email)) {
            $err['email'] = 'Địa chỉ email không hợp lệ !';
        }

        if ($password != $repass) {
            $err['repass'] = 'Mật khẩu nhập lại không khớp!';
        }
        
        $img = 'default.jpg';
        
        if (empty($err)) { 
            $sql = "SELECT * FROM khach_hang WHERE Email_KH = '$email'";
            $query = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($query);
        
            if ($num > 0) {
                $err2['email'] = 'Email đã tồn tại, vui lòng chọn email khác!';
            } else {
                $sql2 = "INSERT INTO khach_hang (Ho_KH, Ten_KH, Email_KH, MatKhau_KH, NgayDangKy)  VALUES ('$lname', '$fname', '$email', '$password', NOW())";

                $query2 = mysqli_query($conn, $sql2);
        
                if (!$query2) {
                    die("Lỗi SQL: " . mysqli_error($conn));
                } else {
                    header('Location: dangnhap.php');
                    exit();
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logo.png">
    <title>Nội thất HUJU</title>
    <link rel="stylesheet" href="css/dangnhap.css">

    <style>
            .close-btn a {
                text-decoration: none;  
                color: inherit;        
                font-size: 20px;        
            }
            
            .close-btn a:hover {
                color: #7fad39;            
            }
    </style>

</head>
<body>
    <div class="login-container">
        <button class="close-btn">
            <a href="home.php">✖</a>
        </button>
        <div class="login-header">
            <h2>ĐĂNG KÝ</h2>
            
        </div>
        <form action="" method="post" role="form">
            <div class="input-group">
                <input type="text" id="lname" name="lname" placeholder="Nhập họ" required>

                <?php
                    if (isset($err['lname'])) {
                        echo '<p style="color: red; font-size: 14px;  margin-top: 5px;">' . $err['lname'] . '</p>';
                    }
                ?>
                
            </div>
            <div class="input-group">
                <input type="text" id="fname" name="fname" placeholder="Nhập tên" required>

                <?php
                    if (isset($err['fname'])) {
                        echo '<p style="color: red; font-size: 14px;  margin-top: 5px;">' . $err['fname'] . '</p>';
                    }
                ?>
                
            </div>
            <div class="input-group">
                <input type="text" id="email" name="email" placeholder="Nhập email" required>
                
                <?php
                    
                    if (isset($err2['email'])) {
                        echo '<p style="color: red; font-size: 14px; margin-top: 5px;">' . $err2['email'] . '</p>';
                    }

                ?>

            </div>
            
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                
            </div>
            <div class="input-group">
                <input type="password" id="repass" name="repass" placeholder="Nhập lại mật khẩu" required>

                <?php
                    if (isset($err['repass'])) {
                        echo '<p style="color: red; font-size: 14px;  margin-top: 5px;">' . $err['repass'] . '</p>';
                    }
                ?>
                
            </div>
            <button type="submit" class="login-btn" name="dangky">Đăng Ký</button>
            <div class="register">
                Bạn đã có tài khoản? <a href="dangnhap.php">Đăng nhập</a>
            </div>
        </form>
    </div>
</body>
</html>
