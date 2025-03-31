<?php
    session_start();
    require_once "connect.php";

   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nội thất HUJU</title>
    <link rel="icon" type="image/png" href="images/logo.png">
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

                    $sql_admin = "SELECT * FROM nhan_vien WHERE Email_NV = '$email' AND MatKhau_NV = '$password'";
                    $query_admin = mysqli_query($conn, $sql_admin);

                    if (mysqli_num_rows($query_admin) > 0) {
                        $row_admin = mysqli_fetch_assoc($query_admin);

                        $_SESSION['id_nv'] = $row_admin['ID_NV'];
                        $_SESSION['email'] = $row_admin['Email_NV'];
                        $_SESSION['username'] = $row_admin['Ten_NV'];
                        $_SESSION['role'] = $row_admin['VaiTro']; 

                        

                        // $user_id = $row_admin['ID_NV']; 
                        // $_SESSION['user_id'] = $user_id;

                        // $cart_cookie_name = "cart_" . $user_id;

                        // if (isset($_COOKIE[$cart_cookie_name])) {
                        //     $_SESSION['cart'] = json_decode($_COOKIE[$cart_cookie_name], true);
                        // } else {
                        //     $_SESSION['cart'] = []; 
                        // }

                        if ($_SESSION['role'] === 'Admin') {
                            header('Location: admin/customer.php'); 
                        } elseif ($_SESSION['role'] === 'Staff') {
                            header('Location: admina/customer.php'); 
                        } else {
                            echo "Vai trò không hợp lệ!";
                            exit();
                        }
                        
                        exit(); 
                    } else {

                    }
                    

                    $sql_user = "SELECT * FROM khach_hang WHERE Email_KH = '$email' AND MatKhau_KH = '$password'";
                    $query_user = mysqli_query($conn, $sql_user);

                    if (mysqli_num_rows($query_user) > 0) {
                        $row_user = mysqli_fetch_assoc($query_user);
                        
                        $_SESSION['ID_KH'] = $row_user['ID_KH'];
                        $_SESSION['email'] = $row_user['Email_KH'];
                        $_SESSION['username'] = $row_user['Ten_KH'];
                        $_SESSION['username1'] = $row_user['Ho_KH'];
                        $_SESSION['role'] = 'customer'; 
                        header('Location: home.php'); 
                        exit();
                    }
                   
                    echo '<p style="border: 2px solid #ebccd1; background-color: #f2dede; color: #a94442;
                    font-size: 15px; text-align: center; padding: 12px; border-radius: 5px">Email hoặc mật khẩu không khớp!</p>';
                    
                   
                    
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


