<?php 

    require('header.php'); 
    require_once "connect.php";
	require "PHPMailer-master/src/PHPMailer.php"; 
	require "PHPMailer-master/src/SMTP.php"; 
	require 'PHPMailer-master/src/Exception.php'; 

?>

<?php
    
    if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
        echo '<script>alert("Bạn cần đăng nhập để thanh toán."); window.location.href = "dangnhap.php";</script>';
        exit(); 
    }
?>
<?php
    $cart = $_SESSION['cart'] ?? []; 
    $checkoutItem = $_SESSION['checkout_item'] ?? null; 
    $itemsToCheckout = $checkoutItem ? [$checkoutItem] : $cart;
    if (isset($_POST['btndathang'])) {
        if (empty($itemsToCheckout)) { 
            echo "<script>
                    alert('Giỏ hàng của bạn đang trống!');
                    window.location.href = 'cart.php';
                </script>";
            exit();
        }

        $id_kh = $_SESSION['ID_KH'];
        $email = $_SESSION['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $phone = $_POST['phone'];
        $state = $_POST['state'];
        $xaphuong = $_POST['xaphuong'];
        $payment_method = $_POST['payment_method'];

        $tong_tien = 0;
        foreach ($itemsToCheckout as $item) {
            $tong_tien += $item['Gia'] * $item['quantity'];
        }
        $tong_tien += 35000; 

        $ngay_tao = date('Y-m-d H:i:s');
        $sqli_don_hang = "INSERT INTO don_hang (ID_KH, ID_TT, NgayTao, TongTien) 
                        VALUES ('$id_kh', '$payment_method', '$ngay_tao', '$tong_tien')";

        if (mysqli_query($conn, $sqli_don_hang)) {
            $last_order_id = mysqli_insert_id($conn); 

            $sqli_dia_chi = "INSERT INTO dia_chi_giao_hang (ID_DH, SoNha, XaPhuong, QuanHuyen, TinhTP, sdt) 
                            VALUES ('$last_order_id', '$state', '$xaphuong', '$address', '$city', '$phone')";
            mysqli_query($conn, $sqli_dia_chi);

            foreach ($itemsToCheckout as $item) {
                $masp = $item['ID_SP'];
                $sl = $item['quantity'];
                $dg = $sl * $item['Gia'];

                $sqli_chi_tiet = "INSERT INTO chi_tiet_don_hang (ID_DH, ID_SP, SoLuong, DonGia) 
                                VALUES ('$last_order_id', '$masp', '$sl', '$dg')";
                mysqli_query($conn, $sqli_chi_tiet);

                $sqli_update_kho = "UPDATE san_pham SET SoLuongKho = SoLuongKho - $sl WHERE ID_SP = '$masp'";
                mysqli_query($conn, $sqli_update_kho);
            }
            if ($checkoutItem) {
                
                unset($_SESSION['checkout_item']);
            } else {
                
                $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($cartItem) use ($itemsToCheckout) {
                    foreach ($itemsToCheckout as $item) {
                        if ($cartItem['ID_SP'] == $item['ID_SP']) {
                            return false; 
                        }
                    }
                    return true; 
                });
                
            }

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->CharSet  = "utf-8";
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ongthanhdai846@gmail.com';
                $mail->Password = 'xotn yagw vpez dzlx';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                $mail->setFrom('ongthanhdai846@gmail.com', 'HUJU SHOP');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Xác nhận đơn hàng - HUJU SHOP';

                $noidungthu = "
                    <h3>Chào bạn,</h3>
                    <p>Bạn vừa đặt hàng tại <b>HUJU SHOP</b>. Dưới đây là thông tin đơn hàng của bạn:</p>
                    <p><b>Mã đơn hàng:</b> {$last_order_id}</p>
                    <p><b>Ngày đặt hàng:</b> " . date('d/m/Y') . "</p>
                    <p><b>Phí vận chuyển:</b> 35,000 VNĐ</p>
                    <p><b>Tổng tiền:</b> " . number_format($tong_tien, 0, '.', ',') . " VNĐ</p>
                    
                    <h4>Chi tiết đơn hàng:</h4>
                    <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; text-align: left;'>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>";

                foreach ($itemsToCheckout as $item) {
                    $ten_sp = $item['Ten_SP']; 
                    $so_luong = $item['quantity'];
                    $don_gia = number_format($item['Gia'], 0, '.', ',') . " VNĐ";
                    $thanh_tien = number_format($item['Gia'] * $so_luong, 0, '.', ',') . " VNĐ";

                    $noidungthu .= "
                        <tr>
                            <td>{$ten_sp}</td>
                            <td>{$so_luong}</td>
                            <td>{$don_gia}</td>
                            <td>{$thanh_tien}</td>
                        </tr>";
                }

                $noidungthu .= "
                    </table>
                    <p>Cảm ơn bạn đã tin tưởng và mua sắm tại HUJU SHOP.</p>
                    <p>Trân trọng,<br><b>HUJU SHOP</b></p>
                ";
                $mail->Body = $noidungthu;
                $mail->send();
                if ($payment_method == 4) { 
                    
                    echo "<script>
                            alert('Chuyển hướng đến trang thanh toán MoMo');
                            window.location.href = 'momo_payment.php?id_dh=$last_order_id';
                        </script>";
                } else {
                    echo "<script>
                            alert('Đặt hàng thành công! Kiểm tra email để xem thông tin đơn hàng.');
                            window.location.href = 'order.php';
                        </script>";
                }
            } catch (Exception $e) {
            //     echo "<script>alert('Đặt hàng thành công nhưng không thể gửi email xác nhận. Vui lòng liên hệ cửa hàng để biết thêm chi tiết.');</script>";
            }
        } else {
            echo "<script>alert('Có lỗi xảy ra khi đặt hàng.');</script>";
        }
    }
?>


     <div class="row">
		<div class="col-lg-6">
			<div class="box-element" id="form-wrapper">
				<form method="POST">
				<div id="user-info">
					<div class="form-field">
						<input required class="form-control" type="text" name="username" placeholder="Họ tên.." 
    						value="<?= isset($_SESSION['username1']) ? htmlspecialchars($_SESSION['username1']) : '' ?> <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '' ?>">

					</div>
					<div class="form-field">
						<input required class="form-control" type="email" name="email" placeholder="Email.." value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>">
					</div>
				</div>


					
					<div id="shipping-info">
					<?php
						if (isset($_SESSION['username'])) {
							$username = $_SESSION['username'];
							$sql_user = "SELECT ID_KH FROM khach_hang WHERE Ten_KH = '$username'";
							$result_user = mysqli_query($conn, $sql_user);
							$row_user = mysqli_fetch_assoc($result_user);
							
							if ($row_user) {
								$user_id = $row_user['ID_KH'];
								$sql = "SELECT d.*, h.*, k.* 
										FROM dia_chi_giao_hang d
										JOIN don_hang h ON d.ID_DH = h.ID_DH
										JOIN khach_hang k ON h.ID_KH = k.ID_KH
										WHERE h.ID_KH = '$user_id'
										ORDER BY h.ID_DH DESC
										LIMIT 1";
								$result = mysqli_query($conn, $sql);
								$payment_info = mysqli_fetch_assoc($result); 
							} else {
								$payment_info = null;
							}
						}
                    ?>
						<hr>
						<p>Thông tin thanh toán:</p>						
						<hr>
						<div class="form-field">
						<input class="form-control" type="text" name="xaphuong" placeholder="Xã/Phường..."
									value="<?= isset($payment_info['XaPhuong']) ? htmlspecialchars($payment_info['XaPhuong']) : '' ?>">
						</div>
						<div class="form-field">
							<input class="form-control" type="text" name="address" placeholder="Quận/Huyện..."
									value="<?= isset($payment_info['QuanHuyen']) ? htmlspecialchars($payment_info['QuanHuyen']) : '' ?>">
						</div>
						
						<div class="form-field">
							<input class="form-control" type="text" name="city" placeholder="Tỉnh/Thành phố..."
									value="<?= isset($payment_info['TinhTP']) ? htmlspecialchars($payment_info['TinhTP']) : '' ?>">
						</div>
						<div class="form-field">
							<input class="form-control" type="text" name="state" placeholder="Số nhà..."
									value="<?= isset($payment_info['SoNha']) ? htmlspecialchars($payment_info['SoNha']) : '' ?>">
						</div>
						<div class="form-field">
							<input class="form-control" type="text" name="phone" placeholder="Số điện thoại..."
									value="<?= isset($payment_info['sdt']) ? htmlspecialchars($payment_info['sdt']) : '' ?>">
						</div>
						<div class="form-field">
							<!-- <input class="form-control" type="text" name="phuongxa" placeholder="Xã/Phường.."> -->
						</div>
						
						<?php 
							$sql_str = "SELECT tt.*
										FROM phuong_thuc_thanh_toan tt
										";
							$result = mysqli_query($conn, $sql_str);
						?>
						<div class="form-field">
							<label for="payment_method">Phương thức thanh toán:</label>
							<select class="form-control" name="payment_method" id="payment_method" required>
								<option value="" disabled selected>Chuyển khoản ngân hàng</option>
								
								<?php								
                                    while ($row = mysqli_fetch_assoc($result)){
                                        echo '<option value="' . htmlspecialchars($row['ID_TT']) . '">' . htmlspecialchars($row['Ten_TT']) . '</option>';
                                    }
								?>
								
							</select>
						</div>                       
					</div>					
					<hr>
					<?php 
                        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                        $checkoutItem = isset($_SESSION['checkout_item']) ? $_SESSION['checkout_item'] : null;
                        $isCartEmpty = empty($cart) && empty($checkoutItem); 
                    ?>

                    <form action="order.php" method="POST">
                        <input 
                            class="btn btn-success btn-block" 
                            style="background-color: #7fad39; color: white; border-color: #7fad39;" 
                            type="submit" 
                            value="Thanh toán" 
                            name="btndathang"
                            <?php echo $isCartEmpty ? 'disabled' : ''; ?> 
                        >

                        <?php if ($isCartEmpty): ?>
                            <p class="text-danger">Giỏ hàng của bạn đang trống. Hãy thêm sản phẩm hoặc chọn "Mua ngay" để tiếp tục thanh toán.</p>
                        <?php endif; ?>
					</form>
				</form>
			</div>
			<br>
		</div>

		<div class="col-lg-6">
            <div class="box-element">
            <a class="btn btn-outline-dark" href="cart.php">&#x2190; Trở về giỏ hàng</a>
            <hr>
            <h3>Thanh toán</h3>
            <hr>
            <div class="cart-row">
                <div style="flex:2"><strong>Tên sản phẩm</strong></div>
                <div style="flex:1"><strong>Hình ảnh</strong></div>
                <div style="flex:1"><strong>Số lượng</strong></div>
                <div style="flex:1"><strong>Đơn giá</strong></div>
                <div style="flex:1"><strong>Thành Tiền</strong></div>
            </div>

            <?php
                $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : []; 
                $checkoutItem = isset($_SESSION['checkout_item']) ? $_SESSION['checkout_item'] : null;
                $count = 0; 
                $total = 0;  
                $shipping_fee = 35000; 
                if ($checkoutItem) {
                    echo '<div class="cart-row">';
                    echo '<div style="flex:2"><p>' . htmlspecialchars($checkoutItem['Ten_SP']) . '</p></div>';
                    echo '<div style="flex:1"><img class="row-image" src="images/' . htmlspecialchars($checkoutItem['HinhAnh']) . '"></div>';
                    echo '<div style="flex:1"><p>' . $checkoutItem['quantity'] . '</p></div>';
                    echo '<div style="flex:1"><p>' . number_format($checkoutItem['Gia'], 0, '.', ',') . ' VND</p></div>';
                    echo '<div style="flex:1"><p>' . number_format($checkoutItem['Gia'] * $checkoutItem['quantity'], 0, '.', ',') . ' VND</p></div>';
                    echo '</div>';
                    
                    $total = $checkoutItem['Gia'] * $checkoutItem['quantity'];
                    $total_with_shipping = $total + $shipping_fee;
                } elseif (!empty($cart)) {
                    foreach ($cart as $productId => $item) {
                        echo '<div class="cart-row">';
                        echo '<div style="flex:2"><p>' . htmlspecialchars($item['Ten_SP']) . '</p></div>';
                        echo '<div style="flex:1"><img class="row-image" src="images/' . htmlspecialchars($item['HinhAnh']) . '"></div>';
                        echo '<div style="flex:1"><p>' . $item['quantity'] . '</p></div>';
                        echo '<div style="flex:1"><p>' . number_format($item['Gia'], 0, '.', ',') . ' VND</p></div>';
                        echo '<div style="flex:1"><p>' . number_format($item['Gia'] * $item['quantity'], 0, '.', ',') . ' VND</p></div>';
                        echo '</div>';

                        $count += $item['quantity']; 
                        $total += $item['Gia'] * $item['quantity'];
                    }
                    
                    $total_with_shipping = $total + $shipping_fee;
                } else {
                    $total_with_shipping = 0;
                    $shipping_fee = 0;
                }
            ?>
                <?php if (isset($_SESSION['checkout_item'])): ?>
                    <h5>Tổng số lượng sản phẩm: <?= $_SESSION['checkout_item']['quantity'] ?></h5>
                <?php else: ?>
                    <h5>Tổng số lượng sản phẩm: <?= $count ?></h5>
                <?php endif; ?>
                <h5>Phí vận chuyển đồng giá: <?= number_format($shipping_fee, 0, '.', ',') ?> VND</h5>
                <h5>Tổng thành tiền: <strong><?= number_format($total_with_shipping, 0, '.', ',') ?> VND</strong></h5>
            </div>
            </div>
	</div>

<?php 
    require('footer.php'); 

?>  