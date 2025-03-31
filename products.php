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

        if ($result && mysqli_num_rows($result) > 0): 
    ?>
        <div class="carousel">
            <div class="carousel-track">
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $file = $row["HinhAnh_L"]; 
                        $avatar_url = "admin/images/anhdanhmuc/" . $file; 
                        $product_name = $row['Ten_L']; 
                        $product_url = "category.php?id=" . $row['ID_L']; 

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
            echo "<p>Không có danh mục nào để hiển thị.</p>"; 
        endif;
    ?>
</div>


<div class="product-container">
    <h2 class="featured-products-title-pd" style="margin-left: 46px;">Tất Cả Các Sản Phẩm</h2>
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
    ?>

    <div class="product-row">
        <?php 
            $sql_str = "SELECT sp.*, bg.Gia, bg.NgayGio, sp.HinhAnh
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
                        WHERE sp.TrangThai_SP = 'active'
                        GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh";
            $result = mysqli_query($conn, $sql_str);
        ?>

        <?php
            while ($row = mysqli_fetch_assoc($result)){
        ?> 
            <div class="product-item">
                <div class="product-image-container">
                    <?php
                        $file = $row["HinhAnh"]; 
                        $avatar_url = "images/" . $file; 

                        if (file_exists($avatar_url)) {
                            echo "<a href='product_detail.php?id={$row['ID_SP']}'>
                                <img src='{$avatar_url}' class='product-image'>
                            </a>";
                        } else {
                            echo "<a href='product_detail.php?id={$row['ID_SP']}'><img src='images/default.jpg' class='product-image'></a>"; // Hiển thị ảnh mặc định nếu không tìm thấy
                        }
                    ?>
                    <?php if ($row['SoLuongKho'] > 0): ?>
                        <form method="POST">
                            <button name="atcicon" type="submit" class="add-to-cart-icon">
                                <i class="fas fa-shopping-cart"></i> 
                            </button>
                            <input type="hidden" value="<?=$row['ID_SP']?>" name="pid">
                            <input type="hidden" value="1" name="quantity"> 
                        </form>
                    <?php else: ?>
                        <p class="add-to-cart-icon">Hết</p>
                    <?php endif; ?>
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