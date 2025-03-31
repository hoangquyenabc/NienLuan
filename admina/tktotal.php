<?php 
    session_start();
    require('includes/header.php'); 
    require_once "connect.php";
?>

<div class="top-bar">
    <div class="breadcrumb">
        Pages / Thống kê
    </div>

    <?php if (isset($_SESSION['username'])): ?>
        <div class="dropdown">
            <!-- Tên người dùng -->
            <a 
                href="#" 
                class="custom-dropdown-toggle" 
                id="userDropdown" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
                <?php echo "Xin chào, " . htmlspecialchars($_SESSION['username']) . " !"; ?>
            </a>

            <!-- Menu Dropdown -->
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="customer.php">Thông tin</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="../dangxuat.php">Đăng xuất</a></li>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php
     // Lấy dữ liệu ngày bắt đầu và ngày kết thúc từ form
   $start_date = isset($_GET['start-date']) ? $_GET['start-date'] : null;
   $end_date = isset($_GET['end-date']) ? $_GET['end-date'] : null;

   // Kiểm tra và định dạng ngày tháng
   if ($start_date && $end_date) {
       $start_date = date('Y-m-d', strtotime($start_date));
       $end_date = date('Y-m-d', strtotime($end_date));
       $date_condition = "WHERE NgayTao BETWEEN '$start_date' AND '$end_date'";
   } else {
       $date_condition = "";
   }

   // Thống kê doanh thu theo ngày
   $sql_sales_by_date = "SELECT DATE(NgayTao) AS sale_date, SUM(TongTien) AS total_sales 
                         FROM don_hang 
                         $date_condition
                         GROUP BY DATE(NgayTao)";
   $result_sales_by_date = $conn->query($sql_sales_by_date);

   $sales_data = [];
   if ($result_sales_by_date->num_rows > 0) {
       while ($row = $result_sales_by_date->fetch_assoc()) {
           $sales_data[] = $row;
       }
   }
   $sales_data_json = json_encode($sales_data);

   // Thống kê số lượng đơn hàng theo ngày
   $sql_orders_by_date = "SELECT DATE(NgayTao) AS order_date, COUNT(ID_DH) AS total_orders 
                          FROM don_hang 
                          $date_condition
                          GROUP BY DATE(NgayTao)";
   $result_orders_by_date = $conn->query($sql_orders_by_date);

   $orders_data = [];
   if ($result_orders_by_date->num_rows > 0) {
       while ($row = $result_orders_by_date->fetch_assoc()) {
           $orders_data[] = $row;
       }
   }
   $orders_data_json = json_encode($orders_data);

    // Chuyển dữ liệu thành JSON để sử dụng trong JavaScript
    $sales_data_json = json_encode($sales_data);
    $orders_data_json = json_encode($orders_data);

   
    $sql_revenue = "SELECT 
                        pt.Ten_TT AS PhuongThucThanhToan, 
                        pt.ID_TT, 
                        SUM(ctdh.SoLuong * bg.Gia) AS total_revenue
                    FROM 
                        chi_tiet_don_hang ctdh
                    JOIN 
                        san_pham sp ON ctdh.ID_SP = sp.ID_SP
                    JOIN 
                        don_hang dh ON ctdh.ID_DH = dh.ID_DH
                    JOIN 
                        phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                    JOIN (
                        SELECT 
                            ID_SP, 
                            Gia
                        FROM 
                            co_gia bg1
                        WHERE 
                            NgayGio = (
                                SELECT 
                                    MAX(NgayGio)
                                FROM 
                                    co_gia bg2
                                WHERE 
                                    bg1.ID_SP = bg2.ID_SP
                            )
                    ) bg ON sp.ID_SP = bg.ID_SP";

                    // Nếu có điều kiện ngày, thêm WHERE/AND phù hợp
                    if (!empty($start_date) && !empty($end_date)) {
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = date('Y-m-d', strtotime($end_date));
                    $sql_revenue .= " WHERE dh.NgayTao BETWEEN '$start_date' AND '$end_date' AND pt.ID_TT = 1";
                    } else {
                    $sql_revenue .= " WHERE pt.ID_TT = 1";
                    }

                    $sql_revenue .= " GROUP BY pt.Ten_TT, pt.ID_TT";

    $result_revenue = $conn->query($sql_revenue);
    $total_revenue = ($result_revenue->num_rows > 0) ? $result_revenue->fetch_assoc()['total_revenue'] : 0;

   
    $sql_products = "SELECT 
                        pt.Ten_TT AS PhuongThucThanhToan, 
                        pt.ID_TT, 
                        SUM(ctdh.SoLuong * bg.Gia) AS total_products_sold
                    FROM 
                        chi_tiet_don_hang ctdh
                    JOIN 
                        san_pham sp ON ctdh.ID_SP = sp.ID_SP
                    JOIN 
                        don_hang dh ON ctdh.ID_DH = dh.ID_DH
                    JOIN 
                        phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                    JOIN (
                        SELECT 
                            ID_SP, 
                            Gia
                        FROM 
                            co_gia bg1
                        WHERE 
                            NgayGio = (
                                SELECT 
                                    MAX(NgayGio)
                                FROM 
                                    co_gia bg2
                                WHERE 
                                    bg1.ID_SP = bg2.ID_SP
                            )
                    ) bg ON sp.ID_SP = bg.ID_SP";

                    // Nếu có điều kiện ngày, thêm WHERE/AND phù hợp
                    if (!empty($start_date) && !empty($end_date)) {
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = date('Y-m-d', strtotime($end_date));
                    $sql_products .= " WHERE dh.NgayTao BETWEEN '$start_date' AND '$end_date' AND pt.ID_TT = 2";
                    } else {
                    $sql_products .= " WHERE pt.ID_TT = 2";
                    }

                    $sql_products .= " GROUP BY pt.Ten_TT, pt.ID_TT";
    $result_products = $conn->query($sql_products);
    $total_products_sold = ($result_products->num_rows > 0) ? $result_products->fetch_assoc()['total_products_sold'] : 0;

    $sql_order = "SELECT 
                        pt.Ten_TT AS PhuongThucThanhToan, 
                        pt.ID_TT, 
                        SUM(ctdh.SoLuong * bg.Gia) AS total_order
                    FROM 
                        chi_tiet_don_hang ctdh
                    JOIN 
                        san_pham sp ON ctdh.ID_SP = sp.ID_SP
                    JOIN 
                        don_hang dh ON ctdh.ID_DH = dh.ID_DH
                    JOIN 
                        phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                    JOIN (
                        SELECT 
                            ID_SP, 
                            Gia
                        FROM 
                            co_gia bg1
                        WHERE 
                            NgayGio = (
                                SELECT 
                                    MAX(NgayGio)
                                FROM 
                                    co_gia bg2
                                WHERE 
                                    bg1.ID_SP = bg2.ID_SP
                            )
                    ) bg ON sp.ID_SP = bg.ID_SP";

                    // Nếu có điều kiện ngày, thêm WHERE/AND phù hợp
                    if (!empty($start_date) && !empty($end_date)) {
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = date('Y-m-d', strtotime($end_date));
                    $sql_order .= " WHERE dh.NgayTao BETWEEN '$start_date' AND '$end_date' AND pt.ID_TT = 3";
                    } else {
                    $sql_order .= " WHERE pt.ID_TT = 3";
                    }

                    $sql_order .= " GROUP BY pt.Ten_TT, pt.ID_TT";
    $result_order = $conn->query($sql_order);
    $total_order = ($result_order->num_rows > 0) ? $result_order->fetch_assoc()['total_order'] : 0;

    $sql_cus = "SELECT 
                        pt.Ten_TT AS PhuongThucThanhToan, 
                        pt.ID_TT, 
                        SUM(ctdh.SoLuong * bg.Gia) AS total_cus
                    FROM 
                        chi_tiet_don_hang ctdh
                    JOIN 
                        san_pham sp ON ctdh.ID_SP = sp.ID_SP
                    JOIN 
                        don_hang dh ON ctdh.ID_DH = dh.ID_DH
                    JOIN 
                        phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                    JOIN (
                        SELECT 
                            ID_SP, 
                            Gia
                        FROM 
                            co_gia bg1
                        WHERE 
                            NgayGio = (
                                SELECT 
                                    MAX(NgayGio)
                                FROM 
                                    co_gia bg2
                                WHERE 
                                    bg1.ID_SP = bg2.ID_SP
                            )
                    ) bg ON sp.ID_SP = bg.ID_SP";

                    // Nếu có điều kiện ngày, thêm WHERE/AND phù hợp
                    if (!empty($start_date) && !empty($end_date)) {
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = date('Y-m-d', strtotime($end_date));
                    $sql_cus .= " WHERE dh.NgayTao BETWEEN '$start_date' AND '$end_date' AND pt.ID_TT = 4";
                    } else {
                    $sql_cus .= " WHERE pt.ID_TT = 4";
                    }

                    $sql_cus .= " GROUP BY pt.Ten_TT, pt.ID_TT";
    $result_cus = $conn->query($sql_cus);
    $total_cus = ($result_cus->num_rows > 0) ? $result_cus->fetch_assoc()['total_cus'] : 0;

    $sql_zalo = "SELECT 
                        pt.Ten_TT AS PhuongThucThanhToan, 
                        pt.ID_TT, 
                        SUM(ctdh.SoLuong * bg.Gia) AS total_zalo
                    FROM 
                        chi_tiet_don_hang ctdh
                    JOIN 
                        san_pham sp ON ctdh.ID_SP = sp.ID_SP
                    JOIN 
                        don_hang dh ON ctdh.ID_DH = dh.ID_DH
                    JOIN 
                        phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                    JOIN (
                        SELECT 
                            ID_SP, 
                            Gia
                        FROM 
                            co_gia bg1
                        WHERE 
                            NgayGio = (
                                SELECT 
                                    MAX(NgayGio)
                                FROM 
                                    co_gia bg2
                                WHERE 
                                    bg1.ID_SP = bg2.ID_SP
                            )
                    ) bg ON sp.ID_SP = bg.ID_SP";

                    // Nếu có điều kiện ngày, thêm WHERE/AND phù hợp
                    if (!empty($start_date) && !empty($end_date)) {
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = date('Y-m-d', strtotime($end_date));
                    $sql_zalo .= " WHERE dh.NgayTao BETWEEN '$start_date' AND '$end_date' AND pt.ID_TT = 5";
                    } else {
                    $sql_zalo .= " WHERE pt.ID_TT = 5";
                    }

                    $sql_zalo .= " GROUP BY pt.Ten_TT, pt.ID_TT";
    $result_zalo = $conn->query($sql_zalo);
    $total_zalo = ($result_zalo->num_rows > 0) ? $result_zalo->fetch_assoc()['total_zalo'] : 0;

    
    $sql_revenue = "SELECT 
                        pt.Ten_TT AS PhuongThucThanhToan, 
                        SUM(ctdh.SoLuong * bg.Gia) AS TongDoanhThu
                    FROM 
                        chi_tiet_don_hang ctdh
                    JOIN 
                        san_pham sp ON ctdh.ID_SP = sp.ID_SP
                    JOIN 
                        don_hang dh ON ctdh.ID_DH = dh.ID_DH
                    JOIN 
                        phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                    JOIN (
                        SELECT 
                            ID_SP, 
                            Gia
                        FROM 
                            co_gia bg1
                        WHERE 
                            NgayGio = (
                                SELECT 
                                    MAX(NgayGio)
                                FROM 
                                    co_gia bg2
                                WHERE 
                                    bg1.ID_SP = bg2.ID_SP
                            )
                    ) bg ON sp.ID_SP = bg.ID_SP
                    $date_condition
                    GROUP BY 
                        pt.Ten_TT
                    ORDER BY 
                        TongDoanhThu DESC;";

        $result_revenue = $conn->query($sql_revenue);

        $revenue_data = [];
        if ($result_revenue->num_rows > 0) {
            while ($row = $result_revenue->fetch_assoc()) {
                $revenue_data[] = $row;
            }
        }
        $revenue_data_json = json_encode($revenue_data);

?>

<div class="container-sp" style="margin-top: 100px;">
<div class="dashboard">
        <header>
            <!-- <h1>Hiệu suất</h1> -->
            <div class="filter">
            <form method="GET" action="">
                <label style="margin-right: 40px;"><strong>Thống kê theo thời gian:</strong></label>
                <label for="start-date">Từ:</label>
                <input type="date" id="start-date" name="start-date" value="<?php echo isset($_GET['start-date']) ? $_GET['start-date'] : ''; ?>">
                
                <label for="end-date">Đến:</label>
                <input type="date" id="end-date" name="end-date" value="<?php echo isset($_GET['end-date']) ? $_GET['end-date'] : ''; ?>">
                
                <button type="submit" style="padding: 3px 10px;
                                background-color: #e91e63;
                                color: #fff;
                                border: none;
                                margin-left: 10px;                             
                                border-radius: 5px;" onclick="thongKe()">Thống kê</button>
            </form>
            </div>
        </header>
        
        <section class="performance">
            <div class="stat">
            <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 16px; margin: 0;">
                Thanh toán khi nhận hàng
            </h3>
                <p><?php echo number_format($total_revenue, 0, '.', ','); ?>VNĐ</p>
            </div>
            <div class="stat">
                <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 16px; margin: 0;">Chuyển khoản ngân hàng</h3>
                <p><?php echo number_format($total_products_sold, 0, '.', ','); ?>VNĐ</p>
            </div>
            <div class="stat">
                <h3>Ví Momo</h3>
                <p><?php echo number_format($total_cus, 0, '.', ','); ?>VNĐ</p>
            </div>
            <div class="stat">
                <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 16px; margin: 0;">Thẻ tín dụng/Thẻ ghi nợ</h3>
                <p><?php echo number_format($total_order, 0, '.', ','); ?>VNĐ</p>
            </div>
            <div class="stat">
                <h3>Ví ZaloPay</h3>
                <p><?php echo number_format($total_zalo, 0, '.', ','); ?>VNĐ</p>
            </div>
            
        </section>
        
        <section class="charts">
            <div class="chart">
                <h3>Doanh số bán hàng</h3>
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </section>
    </div>
    <div class="order-list">
    <h2>Danh sách sản phẩm</h2>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Hình ảnh</th>
                <th>Giá bán</th>
                <th>Số lượng</th>
                
                <!-- <th>stt don hang</th> -->
                <th>Thành tiền</th>
                <th>Phương thức thanh toán</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Truy vấn danh sách đơn hàng từ cơ sở dữ liệu
                $sql = "SELECT 
                            sp.*, 
                            ctdh.*, 
                            bg.Gia, 
                            dh.*, 
                            tt.*, 
                            GROUP_CONCAT(DISTINCT dh.ID_DH ORDER BY dh.ID_DH) AS DanhSachDonHang,  -- Gộp tất cả ID_DH nếu chúng có cùng phương thức thanh toán
                            tt.Ten_TT,
                            SUM(ctdh.SoLuong * bg.Gia) AS TongDoanhThu
                        FROM 
                            chi_tiet_don_hang ctdh
                        JOIN 
                            san_pham sp ON ctdh.ID_SP = sp.ID_SP
                        JOIN 
                            don_hang dh ON ctdh.ID_DH = dh.ID_DH
                        JOIN 
                            phuong_thuc_thanh_toan tt ON tt.ID_TT = dh.ID_TT
                        JOIN (
                            SELECT 
                                ID_SP, 
                                Gia
                            FROM 
                                co_gia bg1
                            WHERE 
                                NgayGio = (
                                    SELECT 
                                        MAX(NgayGio)
                                    FROM 
                                        co_gia bg2
                                    WHERE 
                                        bg1.ID_SP = bg2.ID_SP
                                )
                        ) bg ON sp.ID_SP = bg.ID_SP
                        GROUP BY 
                            sp.ID_SP, 
                            sp.Ten_SP, 
                            ctdh.ID_DH, 
                            dh.ID_DH, 
                            tt.ID_TT, 
                            bg.Gia 
                        ORDER BY 
                            tt.Ten_TT ;

                       ;";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID_SP']); ?></td>
                        <td><?php echo htmlspecialchars($row['Ten_SP']); ?></td>
                        <td>
                            <?php
                                echo '<img src="../images/' . $row['HinhAnh'] . '" style="width: 50px; height: auto; "';
                            ?>
                        </td>

                        <td><?php echo number_format($row['Gia'], 0, '.', ',') . ' VNĐ'; ?></td>
                        <td><?php echo number_format($row['SoLuong'], 0, '.', ',') ?></td>
                        
                        <td><?php echo number_format($row['TongDoanhThu'], 0, '.', ',') . ' VNĐ'; ?></td>
                        <td><?php echo htmlspecialchars($row['Ten_TT']); ?></td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="5">Không có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueData = <?php echo $revenue_data_json; ?>;

        // Xử lý dữ liệu cho Chart.js
        const labels = revenueData.map(item => item.PhuongThucThanhToan);  // Nhãn trục X: tên phương thức thanh toán
        const data = revenueData.map(item => parseFloat(item.TongDoanhThu));  // Dữ liệu trục Y: tổng doanh thu cho mỗi phương thức thanh toán

        // Cấu hình biểu đồ
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar', // Loại biểu đồ: 'bar', 'line', 'pie', etc.
            data: {
                labels: labels, // Nhãn trục X: tên phương thức thanh toán
                datasets: [{
                    label: 'Doanh thu (VND)', // Nhãn tập dữ liệu
                    data: data, // Dữ liệu trục Y: tổng doanh thu cho mỗi phương thức thanh toán
                    backgroundColor: 'rgba(75, 192, 192, 0.5)', // Màu nền
                    borderColor: 'rgba(75, 192, 192, 1)', // Màu viền
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

       
    </script>
    <script>
        function thongKe() {
            let startDate = document.getElementById("start-date").value;
            let endDate = document.getElementById("end-date").value;
            
            if (!startDate || !endDate) {
                alert("Vui lòng chọn đầy đủ khoảng thời gian!");
                return;
            }

            alert(`Thống kê từ ${startDate} đến ${endDate}`);
        }
    </script>
</body>
</html>