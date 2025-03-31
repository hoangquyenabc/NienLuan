<?php 
    require('header.php'); 
    require_once "connect.php";

?>
<section class="contact_section layout_padding">
    <div class="design-box">
      <img src="images/design-2.png" alt="">
    </div>
    <div class="container ">
      <div class="">
        <h2 class="">
          Liên hệ với chúng tôi
        </h2>
      </div>

    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
        <form id="myForm" action="">
            <div>
              <input type="text" placeholder="Họ và Tên" />
            </div>
            <div>
              <input type="email" placeholder="Email" />
            </div>
            <div>
              <input type="text" placeholder="Số điện thoại" />
            </div>
            <div>
              <input type="text" class="message-box" placeholder="Nội dung" />
            </div>
            <div class="d-flex ">
            <button class="submit_button">
                Gửi
            </button>
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <div class="map_container">
            <div class="map-responsive">
            <iframe src="https://www.google.com/maps/d/embed?mid=1XFW-uHReJFO9LRijcIvRSEcWS6s&hl=en_US&ehbc=2E312F" width="600" height="300" frameborder="0" style="border:0; width: 100%; height:100%" allowfullscreen></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php 
    require('footer.php'); 

?>  