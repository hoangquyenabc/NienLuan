<?php 
    require('header.php'); 
    require_once "connect.php";
?>
<div class="container-ab">
  <div class="content">
    <h3>Giúp bạn tỏa sáng</h3>
    <h1>Cùng khám phá vẻ đẹp tự nhiên</h1>
    <p>
      Chúng tôi hiểu rằng vẻ đẹp không chỉ đến từ ngoại hình mà còn từ sự tự tin bên trong. Tại cửa hàng của chúng tôi, chúng tôi cam kết mang đến những sản phẩm và dịch vụ tốt nhất để giúp bạn thể hiện phong cách riêng và tự tin hơn mỗi ngày.
    </p>
    <p>
      Hãy cùng khám phá những xu hướng làm đẹp mới nhất, từ sản phẩm chăm sóc da đến trang điểm, tất cả đều được thiết kế để nâng tầm vẻ đẹp của bạn. Chúng tôi luôn sẵn sàng lắng nghe và chia sẻ kinh nghiệm để bạn có thể tìm thấy những sản phẩm phù hợp nhất với nhu cầu của mình.
    </p>
    <h4 style=" color: #7fad39; margin-right: 353px ; margin-top: 10px;">Đặc điểm nổi bật của sản phẩm</h4>
    <ul class="features">
      <li>Chất lượng cao</li>
      <li>Chiết xuất tự nhiên</li>
      <li>Đa dạng</li>
      <li>Dễ dàng sử dụng</li>
      <li>Đã được kiểm nghiệm</li>
      <li>Bảo vệ môi trường</li>
    </ul>
  </div>
  <div class="image">
    <img src="images/about.jpg" alt="Hình ảnh sản phẩm">
  </div>
</div>
<div class="carousel-inner">
      <div class="carousel-item active" data-bs-interval="10000">
      <img src="images/slideshow_4-edited 1.png" class="d-block w-100" alt="..." height="420" style="margin-top: 100px; padding-left: 200px; padding-right: 200px;">
        <div class="carousel-caption d-none d-md-block text-danger fs-2">
          <!-- <h5 class="display-5">Gioi thieu san pham</h5> -->
          <!-- <p>Kham pha nhung san pham moi den tu cua hang cua chung toi</p> -->
          <button class="buy-now-about" onclick="window.location.href='products.php'">Xem Chi Tiet</button>
        </div>
      </div>
</div>
<section class="service-features">
        <div class="feature-item">
            <img src="images/Discount.png" alt="Khuyến mãi" class="feature-image">
            <h3>Khuyến mãi</h3>
            <p>Đừng bỏ lỡ những ưu đãi hấp dẫn từ những sản phẩm của chúng tôi. Hãy nhanh tay để không bỏ lỡ ngày!</p>
        </div>
        <div class="feature-item">
            <img src="images/Truck.png" alt="Miễn phí vận chuyển" class="feature-image">
            <h3>Miễn phí vận chuyển</h3>
            <p>Hãy thoải mái mua các sản phẩm đạt chất lượng mà không cần lo lắng về phí giao hàng.</p>
        </div>
        <div class="feature-item">
            <img src="images/Batch Assign.png" alt="Chính sách hoàn tiền" class="feature-image">
            <h3>Chính sách hoàn tiền</h3>
            <p>Nếu bạn không hài lòng với sản phẩm, chúng tôi cam kết hoàn lại tiền 100%. Hãy tự tin thử nghiệm.</p>
        </div>
</section>
<?php 
    require('footer.php'); 

?>  