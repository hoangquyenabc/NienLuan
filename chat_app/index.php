<?php
include_once "part/header.php";
?>
<style>

  .close-btn-lg {
      position: absolute; /* Đặt vị trí tuyệt đối */
      top: 202px; /* Khoảng cách từ cạnh trên của form */
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
<body>
  <div class="wrapper">
    <section class="form signup">
    <button class="close-btn-lg">
            <a href="trangchu.php" target="_blank">✖</a>
    </button>
      <header>Đăng Ký</header>
      <form action="#">
        <div class="error-text"></div>

        <!-- name row -->
        <div class="name-details">
          <div class="field input">
            <!-- <label for="">Tên</label> -->
            <input type="text" name="fname" placeholder="Nhập tên" required>
          </div>
          <div class="field input">
            <!-- <label for="">Họ</label> -->
            <input type="text" name="lname" placeholder="Nhập họ" required>
          </div>
        </div>

        <div class="field input">
          <!-- <label for="">Email</label> -->
          <input type="text" name="email" placeholder="Nhập email" required>
        </div>

        <div class="field input">
          <!-- <label for="">Mật khẩu</label> -->
          <input type="password" name="password" placeholder="Nhập mật khẩu" required>
          <!-- <i class="fas fa-eye"></i> -->
        </div>

        <div class="field input">
          <!-- <label for="">Mật khẩu</label> -->
          <input type="password" name="password2" placeholder="Nhập lại mật khẩu" required>
          <!-- <i class="fas fa-eye"></i> -->
        </div>

        <div class="field button">
          <input type="submit" value="Bắt Đầu Chat">
        </div>

      </form>
      <div class="link"> Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a></div>
    </section>
  </div>

  <script src="assets/password-event.js"></script>
  <script src="assets/signup.js"></script>
</body>
</html>