<?php
    session_start();
    require_once "connect.php";

   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
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
            <h2>ĐĂNG NHẬP</h2>
    </div>

        <?php
               if (isset($_POST['dangnhap'])) {
                if (empty($_POST['email']) || empty($_POST['password'])) {
                    echo 'Vui lòng nhập email và mật khẩu!';
                } else {
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    // Kiểm tra trong bảng nhan_vien
                    $sql_admin = "SELECT * FROM nhan_vien WHERE Email_NV = '$email' AND MatKhau_NV = '$password'";
                    $query_admin = mysqli_query($conn, $sql_admin);

                    if (mysqli_num_rows($query_admin) > 0) {
                        // Người dùng là nhân viên
                        $row_admin = mysqli_fetch_assoc($query_admin);
                        $_SESSION['email'] = $row_admin['Email_NV'];
                        $_SESSION['username'] = $row_admin['Ten_NV'];
                        $_SESSION['role'] = 'admin'; // Vai trò là admin
                        header('Location: admin/products.php'); // Chuyển đến trang admin
                        exit();
                    }

                    // Kiểm tra trong bảng khach_hang
                    $sql_user = "SELECT * FROM khach_hang WHERE Email_KH = '$email' AND MatKhau_KH = '$password'";
                    $query_user = mysqli_query($conn, $sql_user);

                    if (mysqli_num_rows($query_user) > 0) {
                        // Người dùng là khách hàng
                        $row_user = mysqli_fetch_assoc($query_user);
                        $_SESSION['ID_KH'] = $row_user['ID_KH'];
                        $_SESSION['email'] = $row_user['Email_KH'];
                        $_SESSION['username'] = $row_user['Ten_KH'];
                        $_SESSION['role'] = 'customer'; // Vai trò là customer
                        header('Location: home.php'); // Chuyển đến trang khách hàng
                        exit();
                    }
                   
                    echo '<p style="border: 2px solid #ebccd1; background-color: #f2dede; color: #a94442;
                    font-size: 15px; text-align: center; padding: 12px; border-radius: 5px">Email hoặc mật khẩu không khớp!</p>';
                    

                    // Nếu không tìm thấy trong cả hai bảng
                    
                }
            }
        ?>

        <form action="" method="post" role="form">
            <div class="input-group">
                <input type="text" id="username" name="email" placeholder="Nhập email" required>
                
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                
            </div>
            <div class="options">
                <label><input type="checkbox" name="remember"> Nhớ mật khẩu</label>
                <a href="quenmk.php">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="login-btn" name="dangnhap">Đăng Nhập</button>
            <div class="register">
                Bạn chưa có tài khoản? <a href="dangky.php">Đăng ký</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php
// session_start();
// if (isset($_POST['dangnhap'])) {
//     if (empty($_POST['email']) || empty($_POST['password'])) {
//         echo 'Vui lòng nhập email và mật khẩu!';
//     } else {
//         $email = $_POST['email'];
//         $password = $_POST['password'];

//         // Kiểm tra trong bảng admins trước
//         $sql_admin = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
//         $query_admin = mysqli_query($conn, $sql_admin);

//         if (mysqli_num_rows($query_admin) > 0) {
//             // Người dùng là admin
//             $row_admin = mysqli_fetch_assoc($query_admin);
//             $_SESSION['email'] = $row_admin['email'];
//             $_SESSION['username'] = $row_admin['fname'];
//             $_SESSION['role'] = 'admin';  // Vai trò là admin
//             header('Location: admin/products.php');  // Chuyển đến trang admin
//             exit();
//         }

//         // Kiểm tra trong bảng users
//         $sql_user = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
//         $query_user = mysqli_query($conn, $sql_user);

//         if (mysqli_num_rows($query_user) > 0) {
//             // Người dùng là khách hàng
//             $row_user = mysqli_fetch_assoc($query_user);
//             $_SESSION['email'] = $row_user['email'];
//             $_SESSION['username'] = $row_user['fname'];
//             $_SESSION['role'] = 'customer';  // Vai trò là customer
//             header('Location: home.php');  // Chuyển đến trang khách hàng
//             exit();
//         }

//         // Nếu không tìm thấy trong cả hai bảng
//         echo '<p style="color:red;">Email hoặc mật khẩu không khớp!</p>';
//     }
// }
// ?>

