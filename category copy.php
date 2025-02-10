<?php 
    require('header.php'); 
    require_once "connect.php";

?>
<div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
          <img src="images/dm-edited.png" class="d-block w-100" alt="..." height="420" style="">
            <div class="carousel-caption d-none d-md-block text-danger fs-2">
              <!-- <h5 class="display-5">Gioi thieu san pham</h5> -->
              <!-- <p>Kham pha nhung san pham moi den tu cua hang cua chung toi</p> -->
              <!-- <button class="buy-now-about" onclick="window.location.href='sanpham.html'">Xem Chi Tiet</button> -->
            </div>
          </div>
</div>
<?php
    if (isset($_GET['id'])) {
        $category_id = intval($_GET['id']); // Ép kiểu số nguyên để bảo mật
    } else {
        echo "<p>ID danh mục không hợp lệ!</p>";
        exit;
    }

    $sql_category = "SELECT Ten_L FROM loai_sp WHERE ID_L = {$category_id} LIMIT 1";
    $result_category = mysqli_query($conn, $sql_category);

    if ($result_category && mysqli_num_rows($result_category) > 0) {
        $category = mysqli_fetch_assoc($result_category);
        $category_name = $category['Ten_L']; // Lấy tên danh mục
    } else {
        $category_name = "Danh mục không tồn tại"; // Hiển thị tên mặc định nếu không tìm thấy
    }
?>

<div class="review-section-ct">
    <h2><h2><?= htmlspecialchars("Nội Thất " . $category_name) ?></h2>
    </h2>
</div>


<!-- Hàng 1 -->
<div class="product-row">
           
<?php 
if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']); // Ép kiểu số nguyên để bảo mật
} else {
    echo "<p>ID danh mục không hợp lệ!</p>";
    exit;
}

$sql_str = "SELECT sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh
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
            WHERE sp.ID_L = {$category_id} 
            ORDER BY sp.Ten_SP 
            LIMIT 4";

$result = mysqli_query($conn, $sql_str);

if ($result && mysqli_num_rows($result) > 0) { 
    while ($row = mysqli_fetch_assoc($result)) {
        $file = $row["HinhAnh"]; // Lấy tên file ảnh từ cơ sở dữ liệu
        $avatar_url = "images/" . $file; // Đường dẫn đầy đủ đến ảnh
?>
        <div class="product-item">
        <div class="product-image-container">
            <?php
                $file = $row["HinhAnh"]; // Lấy tên file ảnh từ cơ sở dữ liệu
                $avatar_url = "images/" . $file; // Đường dẫn đầy đủ đến ảnh

                // Kiểm tra nếu file ảnh tồn tại
                if (file_exists($avatar_url)) {
                    echo "<a href='product_detail.php?id={$row['ID_SP']}'><img src='{$avatar_url}' class='product-image'></a>";
                } else {
                    echo "<a href='product_detail.php?id={$row['ID_SP']}'><img src='images/default.jpg' class='product-image'></a>"; // Hiển thị ảnh mặc định nếu không tìm thấy
                }
            ?>
            <form method="POST">
                <button name="atcicon" type="submit" class="add-to-cart-icon">
                    <i class="fas fa-shopping-cart"></i> 
                </button>
                <input type="hidden" value="<?=$row['ID_SP']?>" name="id"> <!-- ID sản phẩm -->
                <input type="hidden" value="1" name="quantity"> <!-- Số lượng mặc định là 1 -->
            </form>
        </div>
        <h3 class="product-name"><?= htmlspecialchars($row['Ten_SP']) ?></h3>
        <p class="product-price"><?= number_format($row['Gia'], 0, '.', ',') ?> VNĐ</p>
        </div>
<?php 
    }
} else {
    echo "<p>Không có sản phẩm nào trong danh mục này.</p>";
}
?>

    
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