<?php 
    require('header.php'); 
    require_once "connect.php";
    unset($_SESSION['checkout_item']);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box-element">
            <a class="btn btn-outline-dark" href="products.php">&#x2190; Tiếp tục mua sắm</a>
            <br><br>
            <?php

                $cart = $_SESSION['cart'] ?? [];  
                $count = 0;
                $total = 0;

                foreach ($cart as $item) {  
                    $count += $item['quantity']; 
                    $total += $item['Gia'] * $item['quantity']; 
                }
            ?>
            
            <?php if ($count > 0) :  ?> 
                <table class="table">
                    <tr>
                        <th><h5>Tổng số lượng sản phẩm: <strong><?= $count ?></strong></h5></th>
                        <th><h5>Tổng thành tiền: <strong><?= number_format($total, 0, '.', ',') ?> VND</strong></h5></th>
                        <th>
                            <a style="float:right; margin:5px; background-color: #7fad39; color: white; border-color: #7fad39;" class="btn btn-success" href="checkout.php">Thanh toán</a>
                        </th>
                    </tr>
                </table>
            <?php else : ?>
                <p class="text-center text-danger">Giỏ hàng của bạn đang trống!</p>
            <?php endif; ?>
        </div>
        <br>

        <?php if ($count > 0) : ?>
            <div class="box-element">
                <div class="cart-row">
                    <div style="flex:1"><strong>Tên sản phẩm</strong></div>
                    <div style="flex:1"><strong>Hình ảnh</strong></div>
                    <div style="flex:1"><strong>Đơn giá</strong></div>
                    <div style="flex:1"><strong>Số lượng</strong></div>
                    <div style="flex:1"><strong>Thành tiền</strong></div>
                    <div style="flex:1"><strong>Hành động</strong></div>
                </div>

                <?php foreach ($cart as $productId => $item) : ?>
                    <div class="cart-row">
                        <div style="flex:1"><p><?= htmlspecialchars($item['Ten_SP'])  ?></p></div> 
                        <div style="flex:1">
                            <img class="row-image" src="images/<?= htmlspecialchars($item['HinhAnh']) ?>" style="width: 80px; height: auto;">
                        </div>
                        <div style="flex:1"><p><?= number_format($item['Gia'], 0, '.', ',') ?> VND</p></div>

                        <div style="flex:1">
                            <form method="POST" action="updatecart.php?id=<?= htmlspecialchars($item['ID_SP']) ?>">
                                <input type="hidden" name="pid" value="<?= htmlspecialchars($item['ID_SP']) ?>">
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    name="quantity" 
                                    value="<?= $item['quantity'] ?>" 
                                    min="1" 
                                    max="<?= $item['SoLuongKho'] ?>" 
                                    style="width: 80px;">
                        </div>

                        <div style="flex:1">
                            <p><?= number_format($item['Gia'] * $item['quantity'], 0, '.', ',') ?> VND</p>
                        </div>

                        <div style="flex:1">
                            <a href="deletecart.php?id=<?= htmlspecialchars($item['ID_SP']) ?>" class="btn btn-danger btn-sm">Xóa</a>
                            <button type="submit" class="btn btn-warning btn-sm">Cập nhật</button>                       
                        </div>
                            </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php 
    require('footer.php'); 

?>  