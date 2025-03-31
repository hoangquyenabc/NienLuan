<?php
    
    
    require('header.php'); 
    require_once "connect.php";


    $id = $_GET['order_id'];
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
        SELECT sp.*, ctdh.SoLuong, bg.Gia, dh.*
        FROM chi_tiet_don_hang ctdh
        JOIN san_pham sp ON ctdh.ID_SP = sp.ID_SP
        JOIN don_hang dh ON ctdh.ID_DH = dh.ID_DH
        JOIN co_gia bg ON sp.ID_SP = bg.ID_SP
        WHERE ctdh.ID_DH = $id
        AND bg.NgayGio = (
            SELECT MAX(bg2.NgayGio)
            FROM co_gia bg2
            WHERE bg2.ID_SP = sp.ID_SP
            AND bg2.NgayGio <= dh.NgayTao 
        );";

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
        // echo "Không tìm thấy sản phẩm nào trong đơn hàng.";
    }

?>


<div class="container-sp" style="margin-left: 250px; margin-top: 50px;">

    <div class="customer-info" style="display: flex; justify-content: space-between; gap: 20px;">
    <div class="left-side" style="width: 48%;">
        <h3 style="color: #7fad39">Thông tin khách hàng</h3>
        <table  style="width: 100%; border-collapse: collapse; margin-left: 1px;">
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
        <h3 style="color: #7fad39">Chi tiết đơn hàng</h3>
        <table  style="width: 100%; border-collapse: collapse; margin-left: 1px;">
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
                <td style="width: 70%; padding: 8px; text-align: left;"><?php echo htmlspecialchars($customer_status); ?></td>
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
        <div class="header" style="color: #7fad39">Danh sách đơn hàng</div>
        <table  style="margin-left: 1px;">
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
                        echo '<td><img src="images/' . $item['HinhAnh'] . '" style="width: 50px; height: auto;"></td>';
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
                    <td colspan="1" style="text-align: right;"><?= number_format($total_with_shipping, 0, '.', ',') ?> VNĐ</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php 
    require('footer.php'); 

?>  