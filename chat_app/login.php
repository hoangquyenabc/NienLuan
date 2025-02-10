<?php
include_once "part/header.php";
?>
<head>
  <style>

  .close-btn-lg {
      position: absolute; /* Đặt vị trí tuyệt đối */
      top: 245px; /* Khoảng cách từ cạnh trên của form */
      right: 654px; /* Khoảng cách từ cạnh phải của form */
      background-color: #e0e0e0;
      border: none;
      border-radius: 30%;
      font-size: 16px;
      cursor: pointer;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
  }

        .close-btn-lg a {
            text-decoration: none;  
            color: inherit;        
            font-size: 20px;   
                
        }
        
        .close-btn-lg a:hover {
            color: red;            
        }
  </style>
</head>
<body>
  <div class="wrapper">
    <section class="form login">
    <button class="close-btn-lg">
            <a href="trangchu.php" target="_blank">✖</a>
    </button>
      <header>ĐĂNG NHẬP</header>
      <form action="#">
        
        <div class="error-text"></div>


        <div class="field input">
          <!-- <label for="">Email</label> -->
          <input type="text" name="email" placeholder="Nhập Email" required>
        </div>

        
        <div class="field input">
          <!-- <label for="">Mật khẩu</label> -->
          <input type="password" name="password" placeholder="Nhập mật khẩu" required>
          <!-- <i class="fas fa-eye"></i> -->
        </div>
        <div class="options">
                <label><input type="checkbox" name="remember"> Nhớ mật khẩu</label>
                <a href="quenmk.php">Quên mật khẩu?</a>
            </div>
        <div class="field button">
          <input type="submit" value="Bắt Đầu Chat">
        </div>

      </form>
      <div class="link">Bạn chưa có tài khoản? <a href="index.php">Đăng ký</a></div>
    </section>
  </div>

  
  <script src="assets/login.js"></script>
</body>
</html>