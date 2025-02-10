<?php 
    require('header.php'); 
    require_once "connect.php";

?>
<div class="review-section">
    <h2 class="featured-products-title-dmsp">Danh mục sản phẩm</h2>
    <?php 
       $sql_str = "SELECT * 
                    FROM loai_sp 
                    WHERE Ten_L LIKE 'P%' OR Ten_L LIKE 'Đ%' 
                    ORDER BY Ten_L 
                    LIMIT 5;
                    ";
        $result = mysqli_query($conn, $sql_str);

        if ($result && mysqli_num_rows($result) > 0): // Kiểm tra kết quả không rỗng
    ?>
        <div class="carousel">
            <div class="carousel-track">
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $file = $row["HinhAnh_L"]; // Lấy tên file ảnh từ cơ sở dữ liệu
                        $avatar_url = "admin/images/anhdanhmuc/" . $file; // Đường dẫn đầy đủ đến ảnh
                        $product_name = $row['Ten_L']; // Lấy tên danh mục sản phẩm
                        $product_url = "category.php?id=" . $row['ID_L']; // Tạo liên kết đến trang chi tiết sản phẩm

                        // Kiểm tra nếu file ảnh tồn tại
                        if (file_exists($avatar_url)) {
                            echo "
                                <div class='product-item-dm' style='text-align: center;'>
                                    <a href='{$product_url}' style='text-decoration: none; color: inherit;'>
                                        <img src='{$avatar_url}' alt='{$product_name}' class='gallery-image' style='margin: 15px;'>
                                        <p>{$product_name}</p>
                                    </a>
                                </div>
                            ";
                        } else {
                            echo "
                                <div class='product-item-dm' style='text-align: center;'>
                                    <a href='{$product_url}' style='text-decoration: none; color: inherit;'>
                                        <img src='images/default.jpg' alt='Ảnh mặc định' class='gallery-image' style='width: 150px; height: auto; margin: 15px;'>
                                        <p>{$product_name}</p>
                                    </a>
                                </div>
                            ";
                        }
                    }
                ?>
            </div>
        </div>
    <?php 
        else:
            echo "<p>Không có danh mục nào để hiển thị.</p>"; // Hiển thị thông báo nếu không có kết quả
        endif;
    ?>
</div>


<!-- Hàng 1 -->
<div class="product-container">
    <h2 class="featured-products-title-pd" style="margin-left: 46px;">Tất Cả Các Sản Phẩm</h2>
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
                        GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh";
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