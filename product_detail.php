<?php 
    require('header.php'); 
    require_once "connect.php";

?>     
<div class="container my-5">
<?php
    if (isset($_POST['atcbtn'])) {
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

    // Xử lý lấy thông tin sản phẩm từ URL
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
        $id = intval($_POST['pid']); // ID sản phẩm
        $quantity = 1; // Số lượng mặc định là 1, không cần hiển thị cho người dùng
    
        // Kiểm tra nếu giỏ hàng có sẵn, nếu không thì khởi tạo giỏ hàng mới
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $isFound = false;
    
        // Duyệt qua giỏ hàng để kiểm tra sản phẩm đã có trong giỏ hàng chưa
        foreach ($cart as &$item) {
            if ($item['ID_SP'] == $id) {
                // Nếu sản phẩm đã có trong giỏ hàng, không cần thêm lại, chỉ cần tiếp tục
                $isFound = true;
                break;
            }
        }
    
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
        if (!$isFound) {
            // Lấy thông tin sản phẩm từ cơ sở dữ liệu
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
                        WHERE sp.ID_SP = $id";
            $result = mysqli_query($conn, $sql_str);
    
            if ($result && mysqli_num_rows($result) > 0) {
                $product = mysqli_fetch_assoc($result);
                $product['quantity'] = $quantity; // Gán số lượng cho sản phẩm mặc định là 1
                $cart[] = $product; // Thêm sản phẩm vào giỏ hàng
            } else {
                echo "Sản phẩm không tồn tại!";
            }
        }
    
        // Cập nhật giỏ hàng vào session
        $_SESSION['cart'] = $cart;
    
        // Chuyển hướng sang trang thanh toán
        echo '<script>window.location.href = "checkout.php";</script>';
        exit;
    }
?>

    <div class="row">
    <div class="col-md-5">
        <div class="main-img">
            <!-- Khung hình lớn -->
            <img id="mainImage" class="img-fluid" src="images/<?= htmlspecialchars($product['HinhAnh']) ?>" alt="Product Image">

            <!-- Khung ảnh nhỏ -->
            <div class="row my-3 previews">
                <?php
                $anh_arr = explode(';', $product['Hinh']);
                foreach ($anh_arr as $index => $image) {
                    $image = htmlspecialchars($image);
                    ?>
                    <div class="col-md-3">
                        <img class="w-100 small-img" src="images/<?= $image ?>" alt="Image <?= $index + 1 ?>" 
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
                    <!-- <p class="old-price mb-1"><del>1000</del> <span class="old-price-discount text-danger">(20% off)</span></p> -->
                    <p class="new-price text-bold mb-1"><?= number_format($product['Gia'], 0, '.', ',') ?> VNĐ</p>
                    <!-- <p class="text-secondary mb-1">(Additional tax may apply on checkout)</p> -->

                </div>

                <form name="productCategoryForm" id="productCategoryForm" method="POST">
                    <div class="buttons d-flex my-5">
                        <input type="hidden" name="product_id" value="">
                        
                        <!-- Nút thêm vào giỏ hàng -->
                        <div class="block">
                            <button name="atcbtn" type="submit" class="shadow btn custom-btn update-cart">Add to cart</button>
                        </div>
                        
                        <!-- Nút mua ngay -->
                        <form action="" method="POST">
                            <input type="hidden" name="pid" value="<?= $product_id ?>">  <!-- ID sản phẩm -->
                            <button name="buyNow" type="submit" class="shadow btn custom-btn purchase-now">Mua Ngay</button>
                        </form>

                        
                        <!-- Số lượng sản phẩm -->
                        <div class="block quantity" style="margin-left: 5px;">
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" style="width: 80px;">
                        </div>
                        
                        <!-- ID sản phẩm -->
                        <input type="hidden" value="<?=$product_id?>" name="pid">
                </div>
            </form>

            <!-- Form để xóa giỏ hàng -->
            <!-- <form method="POST">
                <button type="submit" name="clear_cart" class="btn btn-danger">Clear Cart</button>
            </form> -->




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
    // Lấy ID sản phẩm từ URL
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Kiểm tra nếu khách hàng đã đăng nhập
    $isLoggedIn = isset($_SESSION['ID_KH']);
    $customerId = $isLoggedIn ? $_SESSION['ID_KH'] : null;

    // Kiểm tra nếu khách hàng đã mua sản phẩm
    $hasPurchased = false;
    $idDonHang = null; // Biến lưu ID đơn hàng
    if ($isLoggedIn) {
        $checkPurchaseSql = "SELECT don_hang.ID_DH, COUNT(*) AS count 
                            FROM chi_tiet_don_hang 
                            JOIN don_hang ON chi_tiet_don_hang.ID_DH = don_hang.ID_DH
                            WHERE don_hang.ID_KH = ? AND chi_tiet_don_hang.ID_SP = ? 
                            GROUP BY don_hang.ID_DH";
        $stmt = $conn->prepare($checkPurchaseSql);
        $stmt->bind_param("ii", $customerId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $hasPurchased = $row['count'] > 0;
            $idDonHang = $row['ID_DH']; // Lấy ID đơn hàng
        }
    }

    // Kiểm tra nếu khách hàng đã đánh giá sản phẩm này trước đó
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
            $hasReviewed = $row['count'] > 0; // Kiểm tra xem khách hàng đã đánh giá sản phẩm chưa
        }
    }

    // Xử lý gửi đánh giá
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

    // Nếu khách hàng chưa mua sản phẩm
    if ($isLoggedIn && !$hasPurchased) {
        // echo "<p class='text-warning'>Bạn cần mua sản phẩm này để có thể đánh giá.</p>";
    }

    // Nếu khách hàng đã đánh giá sản phẩm này rồi
    if ($isLoggedIn && $hasReviewed) {
        // echo "<p class='text-warning'>Bạn đã đánh giá sản phẩm này trước đó.</p>";
    }

    // Nếu khách hàng chưa đăng nhập
    if (!$isLoggedIn) {
        // echo "<p class='text-warning'>Bạn cần <a href='login.php'>đăng nhập</a> để đánh giá sản phẩm.</p>";
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
                    // Lấy danh sách đánh giá từ cơ sở dữ liệu
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
    <h2 class="featured-products-title-pd" style="margin-left: 120px;">Các Sản Phẩm Liên Quan</h2>
<div class="product-row">
           
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

    if (isset($_GET['id'])) {
        $product_id = intval($_GET['id']); // Lấy ID sản phẩm từ URL
        $sql = "SELECT sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh, sp.MoTa, l.ID_L, l.Ten_L
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
            $category_id = $product['ID_L']; // Lấy ID danh mục của sản phẩm
            $category_name = $product['Ten_L']; // Tên danh mục
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
    $sql_related = "SELECT sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh, MAX(dh.ID_DH) AS ID_DH, l.*, dh.ID_DH
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
                    JOIN loai_sp l ON sp.ID_L = l.ID_L
                    WHERE l.ID_L = {$category_id} 
                    AND sp.ID_SP != {$product_id}
                    GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh, l.ID_L;

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
            $file = $related_product["HinhAnh"]; // Lấy tên file ảnh từ cơ sở dữ liệu
            $avatar_url = "images/" . $file; // Đường dẫn đầy đủ đến ảnh

            // Kiểm tra nếu file ảnh tồn tại
            if (file_exists($avatar_url)) {
                // Chỉnh sửa liên kết để đến trang chi tiết của sản phẩm liên quan
                echo "<a href='product_detail.php?id={$related_product['ID_SP']}&order_id={$related_product['ID_DH']}'>
                                <img src='{$avatar_url}' class='product-image'>
                            </a>";
            } else {
                echo "<img src='images/default.jpg' class='product-image'>"; // Hiển thị ảnh mặc định nếu không tìm thấy
            }
        ?>
            <form method="POST">
                <button name="atcicon" type="submit" class="add-to-cart-icon">
                    <i class="fas fa-shopping-cart"></i> 
                </button>
                <input type="hidden" value="<?=$product_id?>" name="pid"> <!-- ID sản phẩm -->
                <input type="hidden" value="1" name="quantity"> <!-- Số lượng mặc định là 1 -->
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