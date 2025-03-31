<?php 
    require('header.php'); 
    require_once "connect.php";

?>     
<div class="container my-5">
<?php
    if (isset($_POST['atcbtn'])) {
        $id = intval($_POST['pid']);
        $quantity = intval($_POST['quantity']);

        $query = "SELECT SoLuongKho FROM san_pham WHERE ID_SP = $id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $availableStock = $row['SoLuongKho']; 

        if ($quantity > $availableStock) {
            echo "<script>alert('Số lượng bạn chọn vượt quá số lượng tồn kho!'); window.history.back();</script>";
            exit;
        }

        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $isFound = false;
        foreach ($cart as &$item) {
            if ($item['ID_SP'] == $id) {
                if (($item['quantity'] + $quantity) > $availableStock) { 
                    echo "<script>alert('Tổng số lượng vượt quá số lượng tồn kho!'); window.history.back();</script>";
                    exit;
                }
                $item['quantity'] += $quantity; 
                $isFound = true;
                break;
            }
        }
          
        if (!$isFound) {
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
                $product['quantity'] = $quantity; 
                $cart[] = $product; 
            } else {
                echo "Sản phẩm không tồn tại!";
            }
        }
    
        $_SESSION['cart'] = $cart;
    
        echo '<script>window.location.href = window.location.href;</script>'; 
    }

    if (isset($_GET['id'])) {
        $product_id = intval($_GET['id']);
        $sql = "SELECT sp.*, bg.Gia, bg.NgayGio
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
                WHERE sp.ID_SP = {$product_id}";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
        } else {
            echo "Sản phẩm không tồn tại!";
        }
    } else {
        echo "ID sản phẩm không hợp lệ!";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buyNow'])) { 
        $id = intval($_POST['pid']); 
        $quantity = intval($_POST['quantity']);
        $sql_str = "SELECT sp.*, bg.Gia
                    FROM san_pham sp
                    JOIN (
                        SELECT ID_SP, Gia
                        FROM co_gia bg1
                        WHERE NgayGio = (
                            SELECT MAX(NgayGio)
                            FROM co_gia bg2
                            WHERE bg1.ID_SP = bg2.ID_SP
                        )
                    ) bg ON sp.ID_SP = bg.ID_SP
                    WHERE sp.ID_SP = $id";
    
        $result = mysqli_query($conn, $sql_str);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            $product['quantity'] = $quantity;
            $product['total_price'] = $product['Gia'] * $quantity;
            $_SESSION['checkout_item'] = $product; 
            echo '<script>window.location.href = "checkout.php";</script>';
            exit;
        } else {
            echo "<script>alert('Sản phẩm không tồn tại!'); window.history.back();</script>";
        }
    }
?>

    <div class="row">
    <div class="col-md-5">
        <div class="main-img">
            <img id="mainImage" class="img-fluid" src="images/<?= htmlspecialchars($product['HinhAnh']) ?>" alt="Product Image">
            <div class="row my-3 previews">
                <?php
                    $anh_arr = explode(';', $product['Hinh']);
                    foreach ($anh_arr as $image) {
                        $image = htmlspecialchars($image);
                ?>
                    <div class="col-md-3">
                        <img class="w-100 small-img" src="images/<?= $image ?>" alt="" 
                             onclick="changeMainImage('images/<?= $image ?>')">
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
        <div class="col-md-7">
            <div class="main-description px-2">
                <div class="category text-bold">
                </div>
                <div class="product-title text-bold my-3">
                    <?= htmlspecialchars($product['Ten_SP']) ?>
                </div>
                <div class="price-area my-4">
                    <p class="old-price mb-1">Số lượng trong kho: <span class="old-price-discount text-danger"><?= htmlspecialchars($product['SoLuongKho']) ?></span></p>
                    <p class="new-price text-bold mb-1"><?= number_format($product['Gia'], 0, '.', ',') ?> VNĐ</p>
                    <!-- <p class="text-secondary mb-1">(Additional tax may apply on checkout)</p> -->
                </div>

                <form name="productCategoryForm" id="productCategoryForm" method="POST">
                    <div class="buttons d-flex my-5">
                        <input type="hidden" name="product_id" value="">
                        <div class="block">
                            <button name="atcbtn" type="submit" class="shadow btn custom-btn">Giỏ hàng</button>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="pid" value="<?= $product_id ?>">
                            <button name="buyNow" type="submit" class="shadow btn custom-btn purchase-now">Mua Ngay</button>
                        </form>
                        <div class="block quantity" style="margin-left: 5px;">
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" 
                                min="1" max="<?= $product['SoLuongKho'] ?>" style="width: 80px;">
                        </div>                    
                        <input type="hidden" value="<?=$product_id?>" name="pid">
                    </div>
                </form>
            </div>

            <div class="product-details my-4">
                <p class="details-title text-color mb-1">Kích Thước</p>
                <p class="description"><?= htmlspecialchars($product['KichThuoc']) ?></p>
            </div>
            <div class="product-details my-4">
                <p class="details-title text-color mb-1">Chất Liệu</p>
                <p class="description"><?= htmlspecialchars($product['ChatLieu']) ?></p>
            </div>
            
            <div class="row questions bg-light p-3">
                <!-- <div class="col-md-1 icon"> -->
                    <!-- <i class="fa-brands fa-rocketchat questions-icon"></i> -->
                </div>
                <!-- <div class="col-md-11 text">
                    Have a question about our products at E-Store? Feel free to contact our representatives via live chat or email.
                </div> -->
            </div>

            <div class="delivery my-4">
                <!-- <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-truck"></i></span> <b>Delivery done in 3 days from date of purchase</b> </p>
                <p class="text-secondary">Order now to get this product delivery</p> -->
            </div>
            <div class="delivery-options my-4">
                <!-- <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-filter"></i></span> <b>Delivery options</b> </p>
                <p class="text-secondary">View delivery options here</p> -->
            </div>
            
        </div>
    </div>
</div>
<?php
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $isLoggedIn = isset($_SESSION['ID_KH']);
    $customerId = $isLoggedIn ? $_SESSION['ID_KH'] : null;
   
    $hasPurchased = false;
    $idDonHang = null; 
    if ($isLoggedIn) {
        $checkPurchaseSql = "SELECT don_hang.ID_DH 
                            FROM chi_tiet_don_hang 
                            JOIN don_hang ON chi_tiet_don_hang.ID_DH = don_hang.ID_DH
                            LEFT JOIN danh_gia dg ON don_hang.ID_DH = dg.ID_DH AND dg.ID_SP = chi_tiet_don_hang.ID_SP
                            WHERE don_hang.ID_KH = ?
                            AND chi_tiet_don_hang.ID_SP = ?
                            AND don_hang.TrangThai_DH = 'Đã hoàn thành'
                            AND dg.ID_DH IS NULL
                            ;";
        $stmt = $conn->prepare($checkPurchaseSql);
        $stmt->bind_param("ii", $customerId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $hasPurchased = true;
            $idDonHang = $row['ID_DH']; 
        }
    }

    $hasReviewed = false;
    if ($isLoggedIn) {
        $checkReviewSql = "SELECT COUNT(*) AS count 
                        FROM danh_gia 
                        WHERE ID_KH = ? AND ID_SP = ?";
        $stmt = $conn->prepare($checkReviewSql);
        $stmt->bind_param("ii", $customerId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $hasReviewed = $row['count'] > 0; 
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $isLoggedIn && $hasPurchased && !$hasReviewed) {
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
        $review = isset($_POST['review']) ? mysqli_real_escape_string($conn, trim($_POST['review'])) : null;
        if ($rating && !empty($review) && $idDonHang) {
            $sql = "INSERT INTO danh_gia (ID_KH, ID_SP, Rating, NoiDung, ID_DH, Created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiss", $customerId, $productId, $rating, $review, $idDonHang);
            if ($stmt->execute()) {
                // echo "<p class='text-success'>Đánh giá của bạn đã được gửi thành công!</p>";
            } else {
                echo "<p class='text-danger'>Lỗi: " . $conn->error . "</p>";
            }
        } else {
            // echo "<p class='text-warning'>Vui lòng chọn đánh giá và nhập nhận xét của bạn.</p>";
        }
    }

?>


<div class="reviews-section my-5" >
    <div class="reviews-list my-3">
        <div class="product-review-section">
            <div class="review-tabs">
                <button class="tab-button" onclick="showTab('description')">Mô tả</button>
                <button class="tab-button" onclick="showTab('ingredients')">Thành phần</button>
                <button class="tab-button" onclick="showTab('instructions')">Hướng dẫn sử dụng</button>
                <button class="tab-button" onclick="showTab('reviews')">Đánh giá</button>
            </div>

            <div id="description" class="tab-content">
                <p><?= htmlspecialchars($product['MoTa']) ?></p>
            </div>
            <div id="ingredients" class="tab-content" style="display:none;">
                <p>Đây là thành phần của sản phẩm...</p>
            </div>
            <div id="instructions" class="tab-content" style="display:none;">
                <p>Đây là hướng dẫn sử dụng sản phẩm...</p>
            </div>
            <div id="reviews" class="tab-content" style="display:none;">
                <div class="reviews-list my-3">
                    <?php
                        $sql = "SELECT r.*, k.Ten_KH 
                                FROM danh_gia r
                                JOIN khach_hang k ON k.ID_KH = r.ID_KH  
                                WHERE r.ID_SP = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $productId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($review = $result->fetch_assoc()) {
                                ?>
                                <div class="review-item border-bottom pb-3 mb-3">
                                    <p class="review-name text-bold mb-1"><?= htmlspecialchars($review['Ten_KH']) ?></p>
                                    <p class="review-rating text-warning mb-1">
                                        <?php for ($i = 0; $i < $review['Rating']; $i++) { ?>
                                            <i class="fa-solid fa-star"></i>
                                        <?php } ?>
                                        <?php for ($i = $review['Rating']; $i < 5; $i++) { ?> 
                                            <i class="fa-regular fa-star"></i>
                                        <?php } ?>
                                    </p>
                                    <p class="review-comment text-secondary"><?= htmlspecialchars($review['NoiDung']) ?></p>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p class='text-secondary'>Chưa có đánh giá nào cho sản phẩm này.</p>";
                        }
                    ?>
                </div>
                
                <?php if ($isLoggedIn && $hasPurchased && !$hasReviewed) { ?>
                    <div class="review-form-container">
                        <h3>Thêm một đánh giá</h3>
                        <form action="" method="POST">
                            <label for="rating">Đánh giá *</label>
                            <div class="rating-stars">
                                <input type="radio" name="rating" value="1" id="star1" required><label for="star1">★</label>
                                <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                                <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                                <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                                <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                            </div>
                            <label for="review">Đánh giá của bạn</label>
                            <textarea id="review" name="review" placeholder="Nhập đánh giá của bạn..." required></textarea>
                            <button type="submit" style="background-color: #7fad39; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                                Gửi
                            </button>
                        </form>
                    </div>
                <?php } elseif ($isLoggedIn && $hasReviewed) { ?>
                    <p class="text-warning">Bạn đã đánh giá sản phẩm này trước đó.</p>
                <?php } elseif (!$hasPurchased) { ?>
                    <p class="text-warning">Bạn cần mua sản phẩm này để thêm đánh giá.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="product-container">
    <h2 class="featured-products-title-pd" style="margin-left: 120px;">Các Sản Phẩm Cùng Loại</h2>
    <div class="product-row">
           
<?php 
    if (isset($_POST['atcicon'])) {
        $id = intval($_POST['pid']);
        $quantity = intval($_POST['quantity']);
        
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $isFound = false;
    
        foreach ($cart as &$item) {
            if ($item['ID_SP'] == $id) {
                $item['quantity'] += $quantity;  
                $isFound = true;
                break;
            }
        }
    
        if (!$isFound) {
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
                $product['quantity'] = $quantity; 
                $cart[] = $product;  
            } else {
                echo "Sản phẩm không tồn tại!";
            }
        }
    
        $_SESSION['cart'] = $cart;
    
        echo '<script>window.location.href = window.location.href;</script>'; 
    }

    if (isset($_GET['id'])) {
        $product_id = intval($_GET['id']);
        $sql = "SELECT sp.*, bg.Gia, bg.NgayGio, l.ID_L, l.Ten_L
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
                JOIN loai_sp l ON sp.ID_L = l.ID_L
                WHERE sp.ID_SP = {$product_id};";
        $result = mysqli_query($conn, $sql);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            $category_id = $product['ID_L']; 
            $category_name = $product['Ten_L']; 
        } else {
            echo "Sản phẩm không tồn tại!";
            exit;
        }
    } else {
        echo "ID sản phẩm không hợp lệ!";
        exit;
    }
?>

<?php
    $sql_related = "SELECT sp.*, bg.Gia, bg.NgayGio, l.*
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
                    JOIN loai_sp l ON sp.ID_L = l.ID_L
                    WHERE l.ID_L = {$category_id} 
                    AND sp.ID_SP != {$product_id}
                    AND sp.TrangThai_SP = 'active'
                    GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh, l.ID_L
                    ";
    $result_related = mysqli_query($conn, $sql_related);

    if ($result_related && mysqli_num_rows($result_related) > 0) {
        while ($related_product = mysqli_fetch_assoc($result_related)) {
            $related_avatar_url = "images/" . $related_product['HinhAnh'];
            $related_product_url = "product_detail.php?id=" . $related_product['ID_SP'];
?> 

    <div class="product-item">
        <div class="product-image-container">
        <?php
            $file = $related_product["HinhAnh"]; 
            $avatar_url = "images/" . $file; 

            if (file_exists($avatar_url)) {
                echo "<a href='product_detail.php?id={$related_product['ID_SP']}'>
                                <img src='{$avatar_url}' class='product-image'>
                            </a>";
            } else {
                echo "<img src='images/default.jpg' class='product-image'>"; 
            }
        ?>
            <form method="POST">
                <button name="atcicon" type="submit" class="add-to-cart-icon">
                    <i class="fas fa-shopping-cart"></i> 
                </button>
                <input type="hidden" value="<?=$product_id?>" name="pid"> 
                <input type="hidden" value="1" name="quantity" >  
            </form>
        </div>
        <h3 class="product-name"><?= htmlspecialchars($related_product['Ten_SP']) ?></h3>
        <p class="product-price"><?= number_format($related_product['Gia'], 0, '.', ',') ?> VNĐ</p>
    </div>
        <?php 
                }  
            } else {
                echo "<p>Không có sản phẩm nào cùng danh mục.</p>";
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