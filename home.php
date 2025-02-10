<?php 
    require('header.php'); 
    require_once "connect.php";

?>
<div id="carouselExampleDark" class="carousel carousel-dark slide">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
            <img src="images/slideshow_5-edited.png" class="d-block w-100" alt="..." height='520'>
            <div class="carousel-caption d-none d-md-block text-danger fs-2">
              <!-- <h5 class="display-5">Gioi thieu san pham</h5> -->
              <!-- <p>Kham pha nhung san pham moi den tu cua hang cua chung toi</p> -->
              <button class="buy-now" onclick="window.location.href='products.php'">Xem Chi Tiet</button>
            </div>
          </div>
          <div class="carousel-item" data-bs-interval="2000">
            <img src="images/slideshow_9-edited.png" class="d-block w-100" alt="..." height='520'>
            <div class="carousel-caption d-none d-md-block text-danger fs-2">
              <!-- <h5 class="display-5">Gioi thieu san pham</h5> -->
              <!-- <p>Kham pha nhung san pham moi den tu cua hang cua chung toi</p> -->
              <button class="buy-now-2" onclick="window.location.href='products.php'">Xem Chi Tiet</button>
            </div>
          </div>
          <div class="carousel-item">
            <img src="images/slideshow_2-edited.png" class="d-block w-100" alt="..." height='520'>
            <div class="carousel-caption d-none d-md-block text-danger fs-2">
              <!-- <h5 class="display-5">Gioi thieu san pham</h5> -->
              <!-- <p>Kham pha nhung san pham moi den tu cua hang cua chung toi</p> -->
              <button class="buy-now-3" onclick="window.location.href='products.php'">Xem Chi Tiet</button>
            </div>
          </div>
          <div class="carousel-item">
            <img src="images/slideshow_1_master-edited.png" class="d-block w-100" alt="..." height='520'>
            <div class="carousel-caption d-none d-md-block text-danger fs-2">
              <!-- <h5 class="display-5">Gioi thieu san pham</h5> -->
              <!-- <p>Kham pha nhung san pham moi den tu cua hang cua chung toi</p> -->
              <button class="buy-now-4" onclick="window.location.href='products.php'">Xem Chi Tiet</button>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>



    

      <div class="product-container">
        <h2 class="featured-products-title">Sản Phẩm Nổi Bật</h2>
        <p class="featured-products-noidung" style="font-size: 17px;">Không gian sống hoàn hảo – Tự tin khẳng định phong cách với những sản phẩm nội thất cao cấp, mang đến vẻ đẹp và sự tiện nghi cho ngôi nhà của bạn.</p>
    <?php 
        if (isset($_POST['atcicon'])) {
            $id = intval($_POST['pid']);
            $quantity = intval($_POST['quantity']);
            
            // Lấy giỏ hàng từ session hoặc khởi tạo giỏ hàng rỗng
            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            $isFound = false;
        
            // Duyệt qua giỏ hàng để kiểm tra sản phẩm đã có trong giỏ hàng chưa
            foreach ($cart as &$item) {
                if ($item['ID_SP'] == $id) {
                    $item['quantity'] += $quantity;  // Cập nhật số lượng
                    $isFound = true;
                    break;
                }
            }
        
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới
            if (!$isFound) {
                // Thực hiện truy vấn để lấy thông tin sản phẩm
                $sql_str = "SELECT sp.*, bg.Gia, bg.NgayGio
                            FROM san_pham sp
                            JOIN (
                                SELECT ID_SP, Gia, NgayGio
                                FROM co_gia bg1
                                WHERE NgayGio = (
                                    SELECT MAX(NgayGio)
                                    FROM co_gia bg2
                                    WHERE bg1.ID_SP = bg2.ID_SP
                                )
                            ) bg ON sp.ID_SP = bg.ID_SP 
                            WHERE sp.ID_SP = $id;";
                $result = mysqli_query($conn, $sql_str);
        
                if ($result && mysqli_num_rows($result) > 0) {
                    $product = mysqli_fetch_assoc($result);
                    $product['quantity'] = $quantity; // Gán số lượng sản phẩm
                    $cart[] = $product;  // Thêm sản phẩm vào giỏ hàng
                } else {
                    echo "Sản phẩm không tồn tại!";
                }
            }
        
            // Cập nhật giỏ hàng vào session
            $_SESSION['cart'] = $cart;
        
            // Để không cần phải tải lại trang, bạn có thể sử dụng JavaScript để cập nhật ngay lập tức.
            echo '<script>window.location.href = window.location.href;</script>'; // Điều hướng lại trang để cập nhật giỏ hàng
        }
    ?>

    <div class="product-row">
        <?php 
            $sql_str = "SELECT sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh, MAX(dh.ID_DH) AS ID_DH
                        FROM san_pham sp
                        JOIN (
                            SELECT ID_SP, Gia, NgayGio
                            FROM co_gia bg1
                            WHERE NgayGio = (
                                SELECT MAX(NgayGio)
                                FROM co_gia bg2
                                WHERE bg1.ID_SP = bg2.ID_SP
                            )
                        ) bg ON sp.ID_SP = bg.ID_SP
                        LEFT JOIN chi_tiet_don_hang cth ON sp.ID_SP = cth.ID_SP
                        LEFT JOIN don_hang dh ON cth.ID_DH = dh.ID_DH
                        WHERE sp.MoTa LIKE '%VI%' -- Điều kiện tìm kiếm trong mô tả
                        GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh
                        LIMIT 4";
            $result = mysqli_query($conn, $sql_str);
        ?>

        <?php
            while ($row = mysqli_fetch_assoc($result)){
        ?> 
            <div class="product-item">
                <div class="product-image-container">
                    <?php
                        $file = $row["HinhAnh"]; // Lấy tên file ảnh từ cơ sở dữ liệu
                        $avatar_url = "images/" . $file; // Đường dẫn đầy đủ đến ảnh

                        // Kiểm tra nếu file ảnh tồn tại
                        if (file_exists($avatar_url)) {
                            echo "<a href='product_detail.php?id={$row['ID_SP']}&order_id={$row['ID_DH']}'>
                                <img src='{$avatar_url}' class='product-image'>
                            </a>";
                        } else {
                            echo "<a href='product_detail.php?id={$row['ID_SP']}'><img src='images/default.jpg' class='product-image'></a>"; // Hiển thị ảnh mặc định nếu không tìm thấy
                        }
                    ?>
                    <form method="POST">
                        <button name="atcicon" type="submit" class="add-to-cart-icon">
                            <i class="fas fa-shopping-cart"></i> 
                        </button>
                        <input type="hidden" value="<?=$row['ID_SP']?>" name="pid"> <!-- ID sản phẩm -->
                        <input type="hidden" value="1" name="quantity"> <!-- Số lượng mặc định là 1 -->
                    </form>
                </div>
                <h3 class="product-name"><?= htmlspecialchars($row['Ten_SP']) ?></h3>
                <p class="product-price"><?= number_format($row['Gia'], 0, '.', ',') ?> VNĐ</p>
            </div>
        <?php 
        }  
        ?>  
    </div>

        <!-- Hàng 2 -->
        <div class="product-row">
        <?php 
            $sql_str = "SELECT sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh, MAX(dh.ID_DH) AS ID_DH
                        FROM san_pham sp
                        JOIN (
                            SELECT ID_SP, Gia, NgayGio
                            FROM co_gia bg1
                            WHERE NgayGio = (
                                SELECT MAX(NgayGio)
                                FROM co_gia bg2
                                WHERE bg1.ID_SP = bg2.ID_SP
                            )
                        ) bg ON sp.ID_SP = bg.ID_SP
                        LEFT JOIN chi_tiet_don_hang cth ON sp.ID_SP = cth.ID_SP
                        LEFT JOIN don_hang dh ON cth.ID_DH = dh.ID_DH
                        WHERE sp.KichThuoc LIKE '%3%' -- Điều kiện tìm kiếm trong mô tả
                        GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh
                        LIMIT 4";
            $result = mysqli_query($conn, $sql_str);
        ?>

        <?php
            while ($row = mysqli_fetch_assoc($result)){
        ?> 
            <div class="product-item">
                <div class="product-image-container">
                    <?php
                        $file = $row["HinhAnh"]; // Lấy tên file ảnh từ cơ sở dữ liệu
                        $avatar_url = "images/" . $file; // Đường dẫn đầy đủ đến ảnh

                        // Kiểm tra nếu file ảnh tồn tại
                        if (file_exists($avatar_url)) {
                            echo "<a href='product_detail.php?id={$row['ID_SP']}&order_id={$row['ID_DH']}'>
                                <img src='{$avatar_url}' class='product-image'>
                            </a>";
                        } else {
                            echo "<a href='product_detail.php?id={$row['ID_SP']}'><img src='images/default.jpg' class='product-image'></a>"; // Hiển thị ảnh mặc định nếu không tìm thấy
                        }
                    ?>
                    <form method="POST">
                        <button name="atcicon" type="submit" class="add-to-cart-icon">
                            <i class="fas fa-shopping-cart"></i> 
                        </button>
                        <input type="hidden" value="<?=$row['ID_SP']?>" name="pid"> <!-- ID sản phẩm -->
                        <input type="hidden" value="1" name="quantity"> <!-- Số lượng mặc định là 1 -->
                    </form>
                </div>
                <h3 class="product-name"><?= htmlspecialchars($row['Ten_SP']) ?></h3>
                <p class="product-price"><?= number_format($row['Gia'], 0, '.', ',') ?> VNĐ</p>
            </div>
        <?php 
        }  
        ?>  
    </div>
    </div>
    <div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
          <img src="images/slideshow_8-edited.png" class="d-block w-100" alt="..." height="420" style="margin-top: 100px; padding-left: 200px; padding-right: 200px;">
            <div class="carousel-caption d-none d-md-block text-danger fs-2">
              <!-- <h5 class="display-5">Gioi thieu san pham</h5> -->
              <!-- <p>Kham pha nhung san pham moi den tu cua hang cua chung toi</p> -->
              <button class="buy-now" onclick="window.location.href='products.php'">Xem Chi Tiet</button>
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
    <div class="product-container">
        <h2 class="featured-products-title">Sản Phẩm Mới Nhất</h2>
        <p class="featured-products-noidung" style="font-size: 17px;">Khám phá những sản phẩm nội thất mới nhất từ cửa hàng chúng tôi, mang đến không gian sống hiện đại và tiện nghi cho bạn.</p>
        <!-- Hàng 1 -->
        <div class="product-row">
        <?php 
            $sql_str = "SELECT sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh, MAX(dh.ID_DH) AS ID_DH
                        FROM san_pham sp
                        JOIN (
                            SELECT ID_SP, Gia, NgayGio
                            FROM co_gia bg1
                            WHERE NgayGio = (
                                SELECT MAX(NgayGio)
                                FROM co_gia bg2
                                WHERE bg1.ID_SP = bg2.ID_SP
                            )
                        ) bg ON sp.ID_SP = bg.ID_SP
                        LEFT JOIN chi_tiet_don_hang cth ON sp.ID_SP = cth.ID_SP
                        LEFT JOIN don_hang dh ON cth.ID_DH = dh.ID_DH
                         WHERE sp.SoLuongKho LIKE '%10%'  -- Điều kiện tìm kiếm trong mô tả
                        GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh
                        LIMIT 4";
            $result = mysqli_query($conn, $sql_str);
        ?>

        <?php
            while ($row = mysqli_fetch_assoc($result)){
        ?> 
            <div class="product-item">
                <div class="product-image-container">
                    <?php
                        $file = $row["HinhAnh"]; // Lấy tên file ảnh từ cơ sở dữ liệu
                        $avatar_url = "images/" . $file; // Đường dẫn đầy đủ đến ảnh

                        // Kiểm tra nếu file ảnh tồn tại
                        if (file_exists($avatar_url)) {
                            echo "<a href='product_detail.php?id={$row['ID_SP']}&order_id={$row['ID_DH']}'>
                                <img src='{$avatar_url}' class='product-image'>
                            </a>";
                        } else {
                            echo "<a href='product_detail.php?id={$row['ID_SP']}'><img src='images/default.jpg' class='product-image'></a>"; // Hiển thị ảnh mặc định nếu không tìm thấy
                        }
                    ?>
                    <form method="POST">
                        <button name="atcicon" type="submit" class="add-to-cart-icon">
                            <i class="fas fa-shopping-cart"></i> 
                        </button>
                        <input type="hidden" value="<?=$row['ID_SP']?>" name="pid"> <!-- ID sản phẩm -->
                        <input type="hidden" value="1" name="quantity"> <!-- Số lượng mặc định là 1 -->
                    </form>
                </div>
                <h3 class="product-name"><?= htmlspecialchars($row['Ten_SP']) ?></h3>
                <p class="product-price"><?= number_format($row['Gia'], 0, '.', ',') ?> VNĐ</p>
            </div>
        <?php 
        }  
        ?>  
    </div>
    </div>
<?php 
    require('footer.php'); 

?>  