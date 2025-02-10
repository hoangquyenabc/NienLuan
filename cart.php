<?php 
    require('header.php'); 
    require_once "connect.php";

?>
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

    
?>

<div class="row">
	<div class="col-lg-12">
		<div class="box-element">

			<a  class="btn btn-outline-dark" href="products.php">&#x2190; Tiếp tục mua sắm</a>

			<br>
			<br>
			<table class="table">
			<?php
				$cart = [];
				if (isset($_SESSION['cart'])) {
					$cart = $_SESSION['cart'];
				}

				$count = 0;  // Số lượng sản phẩm trong giỏ
				$total = 0;  // Tổng thành tiền

				foreach ($cart as $item) {
					$count += $item['quantity']; // Tính tổng số lượng
					$total += $item['Gia'] * $item['quantity']; // Tính tổng thành tiền
                }
			?>
				<tr>
					<th><h5>Tổng số lượng sản phẩm: <strong><?= $count ?></strong></h5></th>
					<th><h5>Tổng thành tiền: <strong><?= number_format($total, 0, '.', ',') ?> VND</strong></h5></th>
					<th>
						<a style="float:right; margin:5px; background-color: #7fad39; color: white; border-color: #7fad39;" class="btn btn-success" href="checkout.php">Thanh toán</a>
					</th>
				</tr>
			<?php
				
			?>
			
		</table>


		</div>

		<br>
		<div class="box-element">
            <div class="cart-row">
                <div style="flex:1"><strong>Tên sản phẩm</strong></div>
                <div style="flex:1"><strong>Hình ảnh</strong></div>
                <div style="flex:1"><strong>Đơn giá</strong></div>
                <div style="flex:1"><strong>Số lượng</strong></div>
                <div style="flex:1"><strong>Thành tiền</strong></div>
                <div style="flex:1"><strong>Hành động</strong></div>
            </div>

            <?php foreach ($cart as $productId => $item) { ?>
                <div class="cart-row">
                    <div style="flex:1"><p><?= htmlspecialchars($item['Ten_SP']) ?></p></div>
                    <div style="flex:1">
                        <img class="row-image" src="images/<?= htmlspecialchars($item['HinhAnh']) ?>" style="width: 80px; height: auto;">
                    </div>
                    <div style="flex:1"><p><?= number_format($item['Gia'], 0, '.', ',') ?> VND</p></div>
                    
                    <div style="flex:1">
                    <form method="POST" action="updatecart.php?id=<?=$item['ID_SP']?>">
                    <!-- Số lượng sản phẩm -->
                    <div class="block quantity">
                        <input 
                            type="number" 
                            class="form-control" 
                            id="quantity" 
                            name="quantity" 
                            value="<?= $item['quantity'] ?>" 
                            min="1" 
                            style="width: 80px;">
                    </div>

                    <!-- ID sản phẩm -->
                    <input type="hidden" value="<?= $item['ID_SP'] ?>" name="pid">
                    </div>

                    <div style="flex:1">
                        <p><?= number_format($item['Gia'] * $item['quantity'], 0, '.', ',') ?> VND</p>
                    </div>

                    <div style="flex:1">
                        
                            <!-- Nút Xóa -->
                            <a href="deletecart.php?id=<?= $item['ID_SP'] ?>" class="btn btn-danger btn-sm">Xóa</a>
                            
                            <!-- Nút Cập nhật -->
                            <button name="qty" type="submit" class="btn btn-warning btn-sm">Cập nhật</button>
                        
                    </div>

                </form> 
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php 
    require('footer.php'); 

?>  