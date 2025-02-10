<?php
class AuthController{
    private Config $conn;

    public function __construct()
    {
        if(!isset($_SESSION)){
            session_start();
        }
        $this->conn = new Config();
    }

    public function checkAuth(){
        if(!isset($_SESSION['unique_id'])){
            header("location: login.php");
        }
    }

    public function signUp(){
        $fname = mysqli_real_escape_string($this->conn->connect(), $_POST['fname']);
        $lname = mysqli_real_escape_string($this->conn->connect(), $_POST['lname']);
        $email = mysqli_real_escape_string($this->conn->connect(), $_POST['email']);
        $password = mysqli_real_escape_string($this->conn->connect(), $_POST['password']);
        $password2 = mysqli_real_escape_string($this->conn->connect(), $_POST['password2']);
    
        // Kiểm tra các trường bắt buộc
        if(empty($fname) || empty($lname) || empty($email) || empty($password)){
            // echo "Mọi trường đều bắt buộc";
            return;
        }
    
        // Kiểm tra định dạng email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo "$email không là email hợp lệ!";
            return;
        }

        if($password !== $password2){
            echo "Mật khẩu và mật khẩu nhập lại không khớp!";
            return;
        }
    
        // Kiểm tra email đã tồn tại
        if($this->checkIssetEmail($email)){
            return;
        }
    
        // Sử dụng ảnh mặc định
        $default_img_name = "default.jpg"; // Tên ảnh mặc định, đảm bảo ảnh này đã có trong thư mục `images/`
    
        $ran_id = rand(time(), 1000000000);
        $status = "Đang hoạt động";
        $encrypt_pass = $password; // Mã hóa mật khẩu nếu cần (có thể dùng password_hash)
    
        // Thêm người dùng vào cơ sở dữ liệu
        $insert_query = mysqli_query($this->conn->connect(),
            "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
             VALUES ({$ran_id}, '${fname}', '${lname}', '${email}', '${encrypt_pass}', '${default_img_name}', '${status}')");
    
        // Xử lý lỗi hoặc thành công
        if(!$insert_query){
            echo "Có lỗi xảy ra. Vui lòng thử lại!";
            return;
        }
    
        // Lấy thông tin người dùng vừa đăng ký
        $select_sql2 = mysqli_query($this->conn->connect(), "SELECT * FROM users WHERE email = '${email}'");
        if(!mysqli_num_rows($select_sql2) > 0){
            echo "Email này không tồn tại!";
            return;
        }
    
        $result = mysqli_fetch_assoc($select_sql2);
        $_SESSION['unique_id'] = $result['unique_id'];
        echo "success";
    }
    
    

    public function logIn(){
        $email = mysqli_real_escape_string($this->conn->connect(), $_POST['email']);
        $password = mysqli_real_escape_string($this->conn->connect(), $_POST['password']);

        if(empty($email) or empty($password)){
            // echo "Mọi trường đều bắt buộc";
            return;
        }

        $sql = mysqli_query($this->conn->connect(), "SELECT * FROM users WHERE email = '${email}'");
        if(!mysqli_num_rows($sql) > 0){
            echo "Email này không tồn tại!";
            return;
        }

        $row = mysqli_fetch_assoc($sql);
        $user_pass = $password;
        $enc_pass = $row['password'];
        if($user_pass !== $enc_pass){
            echo "Email hoặc Mật khẩu không chính xác";
            return;
        }

        $status = "Đang hoạt động";
        $sql2 = mysqli_query($this->conn->connect(),
            "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
        if($sql2){
            $_SESSION['unique_id'] = $row['unique_id'];
            $_SESSION['role'] = $row['vaitro'];
            echo json_encode([
                'status' => 'success',
                'role' => $row['vaitro'], // Trả về vai trò người dùng (0 hoặc 1)
            ]);
        } else {
            echo "Có lỗi xảy ra. Vui lòng thử lại!";
        }
}

    public function logOut(){
        $this->checkAuth();

        $logout_id = mysqli_real_escape_string($this->conn->connect(), $_GET['logout_id']);
        if(isset($logout_id)){
            $status = "Không hoạt động";
            $sql = mysqli_query($this->conn->connect(),
                "UPDATE users SET status = '{$status}' WHERE unique_id={$logout_id}");
            if($sql){
                session_unset();
                session_destroy();
                header("location: ../login.php");
            } else {
                header("location: ../users.php");
            }
        }
    }

    private function checkIssetEmail($email){
        $sql = mysqli_query($this->conn->connect(), "SELECT * FROM users WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){
            echo "$email - Email này đã tồn tại!";
            return true;
        }
        return false;
    }
}