<?php
session_start();
require_once "connect.php"; 

$loi="";
if(isset($_POST['guimail']) == true) {
    
    $email = $_POST['email'];
    $sql = "select * from  khach_hang where Email_KH = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $stmt->store_result(); 
    $count = $stmt->num_rows;


    if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/", $email)) {
        $err['email'] = 'Địa chỉ email không hợp lệ !';
    }  
    else
    {
        if($count==0){
            $loi = "Email chưa được đăng ký tài khoản !";
        }
        else {
            $matkhaumoi = substr(md5(rand(0,999999)), 0, 8);
            $sql = "update khach_hang set MatKhau_KH = ? where Email_KH = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $matkhaumoi, $email); 
            $stmt->execute();
    
            require "PHPMailer-master/src/PHPMailer.php"; 
            require "PHPMailer-master/src/SMTP.php"; 
            require 'PHPMailer-master/src/Exception.php'; 
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->SMTPDebug = 0; //0,1,2: chế độ debug
                $mail->isSMTP();  
                $mail->CharSet  = "utf-8";
                $mail->Host = 'smtp.gmail.com';  
                $mail->SMTPAuth = true; 
                $mail->Username = 'ongthanhdai846@gmail.com'; 
                $mail->Password = 'xotn yagw vpez dzlx';   
                $mail->SMTPSecure = 'ssl';  
                $mail->Port = 465;                
                $mail->setFrom('ongthanhdai846@gmail.com', 'HUJU SHOP' ); 
                $mail->addAddress($email); 
                $mail->isHTML(true);  
                $mail->Subject = 'Khôi phục mật khẩu - HUJU SHOP';
                $noidungthu = "
                    <h3>Chào bạn,</h3>
                    <p>Bạn đã yêu cầu khôi phục mật khẩu cho tài khoản tại <b>HUJU SHOP</b>.</p>
                    <p><b>Mật khẩu mới của bạn là:</b> <span style='font-size: 18px; font-weight: bold; color: red;'>{$matkhaumoi}</span></p>
                    <p>Vui lòng đăng nhập lại và thay đổi mật khẩu để đảm bảo an toàn.</p>
                    <p>Nếu bạn không yêu cầu đổi mật khẩu, vui lòng bỏ qua email này hoặc liên hệ ngay với chúng tôi để được hỗ trợ.</p>
                    <p>Trân trọng,<br><b>HUJU SHOP</b></p>
                ";

                $mail->Body = $noidungthu;
                $mail->send();
                echo "<script>
                        alert('Gửi mail thành công!');
                        window.location.href = 'dangnhap.php';
                    </script>";

            } catch (Exception $e) {
                echo 'Error: ', $mail->ErrorInfo;
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
            <a href="home.php" >✖</a>
        </button>
    <div class="login-header">
            <h2>QUÊN MẬT KHẨU</h2>
    </div>

                <?php 
                    if($loi != ""){
                        ?>
                        <div style="border: 2px solid #ebccd1; background-color: #f2dede; color: #a94442;
                            font-size: 15px; text-align: center; padding: 15px; border-radius: 5px"><?= $loi ?></div>
                <?php } ?>
        
        <form action="" method="post" role="form">

                <div class="input-group">

                
                </div>

            <div class="input-group">
                <input value="<?php  if(isset($email)==true) echo $email?>" type="text" id="email" name="email" placeholder="Nhập email" required>
                <?php
                    if (isset($err['email'])) {
                        echo '<p style="color: red; font-size: 14px;  margin-top: 5px;">' . $err['email'] . '</p>';
                    }
                ?>

                
            </div>
       
            <button type="submit" class="login-btn" name="guimail">Gửi email</button>
            <div class="register">
                Bạn đã có tài khoản? <a href="dangnhap.php">Đăng nhập</a>
            </div>
        </form>
    </div>
</body>
</html>
