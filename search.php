<?php 
    require('header.php'); 
    require_once "connect.php";

    if (isset($_GET['search_query'])) {
        $search_query = mysqli_real_escape_string($conn, $_GET['search_query']); 
        
        $sql_str = " 
            SELECT sp.*, bg.Gia, bg.NgayGio, sp.HinhAnh
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
            WHERE sp.Ten_SP LIKE '%$search_query%'
            GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh
            ORDER BY sp.Ten_SP
        ";
        $result = mysqli_query($conn, $sql_str);
    }

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
                
                        WHERE sp.ID_SP = $id
                        GROUP BY sp.ID_SP, sp.Ten_SP, bg.Gia, bg.NgayGio, sp.HinhAnh";
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


<div class="product-container">
    <h2 class="featured-products-title">Kết Quả Tìm Kiếm</h2>
    <?php if (isset($result) && mysqli_num_rows($result) > 0) { ?>
        <div class="product-row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="product-item">
                    <div class="product-image-container">
                        <?php
                            $file = $row["HinhAnh"]; 
                            $avatar_url = "images/" . $file;

                            $image_url = file_exists($avatar_url) ? $avatar_url : 'images/default.jpg';
                        ?>
                        <a href="product_detail.php?id=<?= $row['ID_SP'] ?>">
                            <img src="<?= $image_url ?>" class="product-image" alt="<?= htmlspecialchars($row['Ten_SP']) ?>">
                        </a>
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
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>Không tìm thấy sản phẩm nào với từ khóa: "<?= htmlspecialchars($search_query) ?>"</p>
    <?php } ?>
</div>


<?php 
    require('footer.php'); 
?>
