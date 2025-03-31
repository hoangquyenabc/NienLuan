<?php
    session_start();
    require('includes/header.php');
    require_once "connect.php";
    require __DIR__ . "/../PHPMailer-master/src/PHPMailer.php";
    require __DIR__ . "/../PHPMailer-master/src/SMTP.php";
    require __DIR__ . "/../PHPMailer-master/src/Exception.php";

    $id = $_GET['id'];
    $sql = "SELECT d.*, h.*, k.*
            FROM dia_chi_giao_hang d
            JOIN don_hang h ON d.ID_DH = h.ID_DH
            JOIN khach_hang k ON h.ID_KH = k.ID_KH
            WHERE h.ID_DH = $id";
    $result1 = mysqli_query($conn, $sql);
    if ($result1 && mysqli_num_rows($result1) > 0) {
        $order_info = mysqli_fetch_assoc($result1); 
        $customer_name = $order_info['Ten_KH'];
        $customer_name1 = $order_info['Ho_KH'];
        $customer_email = $order_info['Email_KH'];
        $customer_phone = $order_info['sdt'];
        $customer_status = $order_info['TrangThai_DH'];
        $customer_id = $order_info['ID_DH'];
        $customer_ld = $order_info['LyDoHuy'];
        $customer_date = $order_info['NgayTao'];
        $customer_address = $order_info['SoNha'];
        $customer_address2 = $order_info['XaPhuong'];
        $customer_address3 = $order_info['QuanHuyen'];
        $customer_address4 = $order_info['TinhTP'];
        $full_address = $customer_address . ', ' . $customer_address2 . ', ' . $customer_address3 . ', ' . $customer_address4;
        $full_name = $customer_name1 . ' ' . $customer_name;
    } else {
        echo "Không tìm thấy thông tin đơn hàng.";
    }

    $cart = []; 
    $count = 0;  
    $total = 0;  
    $shipping_fee = 35000;
    $sql_cart = "
        SELECT sp.Ten_SP, sp.HinhAnh, ctdh.SoLuong, bg.Gia
        FROM chi_tiet_don_hang ctdh
        JOIN san_pham sp ON ctdh.ID_SP = sp.ID_SP
        JOIN (
            SELECT ID_SP, Gia
            FROM co_gia bg1
            WHERE NgayGio = (
                SELECT MAX(NgayGio)
                FROM co_gia bg2
                WHERE bg1.ID_SP = bg2.ID_SP
            )
        ) bg ON sp.ID_SP = bg.ID_SP
        WHERE ctdh.ID_DH = $id;
    ";

    $result_cart = mysqli_query($conn, $sql_cart);

    if ($result_cart && mysqli_num_rows($result_cart) > 0) {
        while ($row = mysqli_fetch_assoc($result_cart)) {
            $cart[] = [
                'Ten_SP' => $row['Ten_SP'],
                'HinhAnh' => $row['HinhAnh'],
                'quantity' => $row['SoLuong'],
                'Gia' => $row['Gia'],
            ];
            $count += $row['SoLuong'];
            $total += $row['SoLuong'] * $row['Gia'];
            $total_with_shipping = $total + $shipping_fee;
        }
    } else {
        echo "Không tìm thấy sản phẩm nào trong đơn hàng.";
    }
    if (isset($_POST['update_status'])) {
        if (isset($_POST['order_status'])) { 
            $new_status = mysqli_real_escape_string($conn, $_POST['order_status']); // Loai bo ky tu dac biet
            $id_order = intval($id); 
            $cancel_reason = isset($_POST['cancel_reason']) ? mysqli_real_escape_string($conn, $_POST['cancel_reason']) : ''; 
        } else {
            echo '<script>alert("Không nhận được trạng thái mới!");</script>';
            exit;
        }
        if ($new_status === "Đã hủy") {
            $update_sql = "UPDATE don_hang dh
                            JOIN chi_tiet_don_hang ctdh ON dh.ID_DH = ctdh.ID_DH
                            JOIN san_pham sp ON ctdh.ID_SP = sp.ID_SP
                            SET dh.TrangThai_DH = '$new_status', 
                                LyDoHuy = '$cancel_reason',
                                sp.SoLuongKho = sp.SoLuongKho + ctdh.SoLuong
                            WHERE dh.ID_DH = $id_order;";
        } else {
            $update_sql = "UPDATE don_hang SET TrangThai_DH = '$new_status' WHERE ID_DH = $id_order";
        }
    
        $result_update = mysqli_query($conn, $update_sql);
    
        if ($result_update) {
            echo '<script>alert("Cập nhật trạng thái đơn hàng thành công!");</script>';
            echo '<script>window.location.href = window.location.href;</script>'; 
            if ($new_status === "Đã hủy") {
                $sql_customer = "SELECT kh.Email_KH FROM khach_hang kh JOIN don_hang dh ON kh.ID_KH = dh.ID_KH WHERE dh.ID_DH = $id_order";
                $result_customer = mysqli_query($conn, $sql_customer);
                $customer_data = mysqli_fetch_assoc($result_customer);
    
                if ($customer_data) {
                    $email = $customer_data['Email_KH'];

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
                        $mail->Subject = 'Thông báo hủy đơn hàng - HUJU SHOP';
    
                        $noidungthu = "
                            <h3>Chào bạn,</h3>
                            <p>Chúng tôi xin thông báo rằng đơn hàng <b>#{$id_order}</b> của bạn đã bị hủy.</p>
                            <p><b>Lý do hủy:</b> " . (!empty($cancel_reason) ? $cancel_reason : "Không có lý do cụ thể") . "</p>
                            <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>
                            <p>Trân trọng,<br><b>HUJU SHOP</b></p>
                        ";
    
                        $mail->Body = $noidungthu;
                        $mail->send();
                    } catch (Exception $e) {
                        echo '<script>alert("Lỗi gửi email thông báo hủy đơn hàng: ' . $mail->ErrorInfo . '");</script>';
                    }
                }
            }
        } else {
            echo '<script>alert("Có lỗi xảy ra khi cập nhật trạng thái!");</script>';
            echo mysqli_error($conn);
        }
    }
?>
<div class="top-bar">
    <div class="breadcrumb">
        Pages / Đơn hàng
    </div>

    <?php if (isset($_SESSION['username'])): ?>
        <div class="dropdown">
            <a href="#" class="custom-dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo "Xin chào, " . htmlspecialchars($_SESSION['username']) . " !"; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="customer.php">Thông tin</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="../dangxuat.php">Đăng xuất</a></li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div class="container-sp">

    <div class="customer-info" style="display: flex; justify-content: space-between; gap: 20px;">
    <div class="left-side" style="width: 48%;">
        <h3 style="color: #e91e63;">Thông tin khách hàng</h3>
        <table style="width: 100%; border-collapse: collapse; margin-left: 1px;">
            <tr>
                <th style="width: 30%; padding: 8px; text-align: left;">Tên khách hàng</th>
                <td style="width: 70%; padding: 8px; text-align: left;"><?php echo htmlspecialchars($full_name); ?></td>
            </tr>
            <tr>
                <th style="width: 30%; padding: 8px; text-align: left;">Email</th>
                <td style="width: 70%; padding: 8px; text-align: left;"><?php echo htmlspecialchars($customer_email); ?></td>
            </tr>
            <tr>
                <th style="width: 30%; padding: 8px; text-align: left;">Số điện thoại</th>
                <td style="width: 70%; padding: 8px; text-align: left;"><?php echo htmlspecialchars($customer_phone); ?></td>
            </tr>
            <tr>
                <th style="width: 30%; padding: 8px; text-align: left;">Địa chỉ giao hàng</th>
                <td style="width: 70%; padding: 8px; text-align: left;"><?php echo htmlspecialchars($full_address); ?></td>
            </tr>
        </table>
    </div>
    <div class="right-side" style="width: 48%;">
        <h3 style="color: #e91e63;">Chi tiết đơn hàng</h3>
        <table style="width: 100%; border-collapse: collapse; margin-left: 1px;">
            <tr>
                <th style="width: 30%; padding: 8px; text-align: left;">Mã đơn hàng</th>
                <td style="width: 70%; padding: 8px; text-align: left;"><?php echo htmlspecialchars($customer_id); ?></td>
            </tr>
            <tr>
                <th style="width: 30%; padding: 8px; text-align: left;">Ngày đặt hàng</th>
                <td style="width: 70%; padding: 8px; text-align: left;"><?php echo htmlspecialchars($customer_date); ?></td>
            </tr>
            
            <tr>
                <th style="width: 30%; padding: 8px; text-align: left;">Trạng thái</th>
                <td style="width: 70%; padding: 8px; text-align: left;">
                    <form method="POST" action="">
                        <select name="order_status" id="order_status" style="padding: 6px; width: 70%;" onchange="toggleReasonInput()">
                            <option value="Đang chờ xử lý" <?php echo $customer_status === 'Đang chờ xử lý' ? 'selected' : ''; ?>>Đang chờ xử lý</option>
                            <option value="Đã hoàn thành" <?php echo $customer_status === 'Đã hoàn thành' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                            <option value="Đã hủy" <?php echo $customer_status === 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                            <option value="Đã thanh toán" <?php echo $customer_status === 'Đã thanh toán' ? 'selected' : ''; ?>>Đã thanh toán</option>
                            <option value="Đang giao hàng" <?php echo $customer_status === 'Đang giao hàng' ? 'selected' : ''; ?>>Đang giao hàng</option>
                        </select>

                        <div id="cancel_reason_container" style="display: none; margin-top: 10px;">
                            <label for="cancel_reason">Lý do hủy:</label>
                            <textarea name="cancel_reason" id="cancel_reason" style="width: 100%; padding: 6px;"></textarea>
                        </div>

                        <button type="submit" name="update_status" style="padding: 7px; margin-top: 10px; background-color: #e91e63; color: white; border-radius: 5px; border: white;">Cập nhật</button>
                    </form>
                </td>
            </tr>
            <?php if (!empty($customer_ld)): ?>
            <tr>
                <th style="width: 30%; padding: 8px; text-align: left;">Lý do hủy</th>
                <td style="width: 70%; padding: 8px; text-align: left;"><?php echo htmlspecialchars($customer_ld); ?></td>
            </tr>
            <?php endif; ?>



        </table>
    </div>
</div>
    <div class="product-list">
        <div class="header">Danh sách đơn hàng</div>
        <table style="margin-left: 1px;">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Giá bán</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <?php
                    $stt = 1; 
                    foreach ($cart as $item) {
                        echo '<tr>';
                        echo '<td>' . $stt++ . '</td>';
                        echo '<td>' . $item['Ten_SP'] . '</td>';
                        echo '<td><img src="../images/' . $item['HinhAnh'] . '" style="width: 50px; height: auto;"></td>';
                        echo '<td>' . number_format($item['Gia'], 0, '.', ',') . ' VNĐ</td>';
                        echo '<td>' . $item['quantity'] . '</td>';
                        echo '<td>' . number_format($item['Gia'] * $item['quantity'], 0, '.', ',') . ' VNĐ</td>';
                        echo '</tr>';
                    }
                ?>
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">Tổng số lượng</td>
                    <td colspan="1" style="text-align: right; "><?= $count ?></td>
                    
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">Phí vận chuyển</td>
                    <td colspan="1" style="text-align: right;"><?= number_format($shipping_fee, 0, '.', ',') ?> VNĐ</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">Tổng cộng</td>
                    <td colspan="1" style="text-align: right; "><?= number_format($total_with_shipping, 0, '.', ',') ?> VNĐ</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
