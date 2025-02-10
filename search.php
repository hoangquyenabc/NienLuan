<?php 
    require('header.php'); 
    require_once "connect.php";

    // Kiểm tra nếu có từ khóa tìm kiếm
    if (isset($_GET['search_query'])) {
        $search_query = mysqli_real_escape_string($conn, $_GET['search_query']); // Lấy từ khóa tìm kiếm

        // Truy vấn sản phẩm theo từ khóa tìm kiếm
        $sql_str = " 
            SELECT sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh, MAX(dh.ID_DH) AS ID_DH
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
            WHERE sp.Ten_SP LIKE '%$search_query%' -- Tìm kiếm theo tên sản phẩm
            GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh
            ORDER BY sp.Ten_SP
        ";
        $result = mysqli_query($conn, $sql_str);
    }

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
                        WHERE sp.ID_SP = $id
                        GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh";
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


<div class="product-container">
    <h2 class="featured-products-title">Kết Quả Tìm Kiếm</h2>

    <!-- Kiểm tra nếu có sản phẩm trả về từ truy vấn -->
    <?php if (isset($result) && mysqli_num_rows($result) > 0) { ?>
        <div class="product-row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="product-item">
                    <div class="product-image-container">
                        <?php
                            $file = $row["HinhAnh"]; // Lấy tên file ảnh từ cơ sở dữ liệu
                            $avatar_url = "images/" . $file; // Đường dẫn đầy đủ đến ảnh

                            // Kiểm tra nếu file ảnh tồn tại
                            $image_url = file_exists($avatar_url) ? $avatar_url : 'images/default.jpg';
                        ?>
                        <a href="product_detail.php?id=<?= $row['ID_SP'] ?>&order_id=<?= $row['ID_DH'] ?>">
                            <img src="<?= $image_url ?>" class="product-image" alt="<?= htmlspecialchars($row['Ten_SP']) ?>">
                        </a>
                        <form method="POST">
                            <button name="atcicon" type="submit" class="add-to-cart-icon">
                                <i class="fas fa-shopping-cart"></i> 
                            </button>
                            <input type="hidden" value="<?= $row['ID_SP'] ?>" name="pid"> <!-- ID sản phẩm -->
                            <input type="hidden" value="1" name="quantity"> <!-- Số lượng mặc định là 1 -->
                        </form>
                    </div>
                    <h3 class="product-name"><?= htmlspecialchars($row['Ten_SP']) ?></h3>
                    <p class="product-price"><?= number_format($row['Gia'], 0, '.', ',') ?> VNĐ</p>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>Không tìm thấy sản phẩm nào với từ khóa: "<?= htmlspecialchars($search_query) ?>"</p>
    <?php } ?>
</div>


<?php 
    require('footer.php'); 
?>
