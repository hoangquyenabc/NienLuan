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

    $sql_revenue = "SELECT SUM(TongTien) AS total_revenue 
                    FROM don_hang 
                    $date_condition";
    $result_revenue = $conn->query($sql_revenue);
    $total_revenue = ($result_revenue->num_rows > 0) ? $result_revenue->fetch_assoc()['total_revenue'] : 0;

    // Lấy tổng số sản phẩm đã bán
    $sql_products = "SELECT SUM(SoLuong) AS total_products_sold 
                     FROM chi_tiet_don_hang 
                     JOIN don_hang dh ON chi_tiet_don_hang.ID_DH = dh.ID_DH
                     $date_condition";
    $result_products = $conn->query($sql_products);
    $total_products_sold = ($result_products->num_rows > 0) ? $result_products->fetch_assoc()['total_products_sold'] : 0;

    // Tổng số đơn hàng
    $sql_order = "SELECT COUNT(ID_DH) AS total_order 
                  FROM don_hang 
                  $date_condition";
    $result_order = $conn->query($sql_order);
    $total_order = ($result_order->num_rows > 0) ? $result_order->fetch_assoc()['total_order'] : 0;

    // Tổng số khách hàng
    $sql_cus = "SELECT COUNT(ID_KH) AS total_cus FROM khach_hang";
    $result_cus = $conn->query($sql_cus);
    $total_cus = ($result_cus->num_rows > 0) ? $result_cus->fetch_assoc()['total_cus'] : 0;
    
    $sql_revenue = "SELECT sp.*, ctdh.*, bg.Gia, dh.*, SUM(dh.TongTien) AS TongDoanhThu
                    FROM chi_tiet_don_hang ctdh
                    JOIN san_pham sp ON ctdh.ID_SP = sp.ID_SP
                    JOIN don_hang dh ON ctdh.ID_DH = dh.ID_DH
                    
                    JOIN (
                        SELECT ID_SP, Gia
                        FROM co_gia bg1
                        WHERE NgayGio = (
                            SELECT MAX(NgayGio)
                            FROM co_gia bg2
                            WHERE bg1.ID_SP = bg2.ID_SP
                        )
                    ) bg ON sp.ID_SP = bg.ID_SP
                    $date_condition
                    GROUP BY sp.ID_SP, ctdh.ID_DH, dh.ID_DH, sp.Ten_SP  -- Các trường không phải hàm tổng hợp phải có trong GROUP BY
                    ORDER BY TongDoanhThu DESC;

                    ";
    $result_revenue = $conn->query($sql_revenue);

    $revenue_data = [];
    if ($result_revenue->num_rows > 0) {
        while ($row = $result_revenue->fetch_assoc()) {
            $revenue_data[] = $row;
        }
    }
    $revenue_data_json = json_encode($revenue_data);

    // Lấy dữ liệu sản phẩm bán chạy
    $sql_best_selling = "SELECT s.Ten_SP, SUM(ctdh.SoLuong) AS TongSoLuong
                         FROM chi_tiet_don_hang ctdh
                         JOIN san_pham s ON ctdh.ID_SP = s.ID_SP
                         JOIN don_hang dh ON ctdh.ID_DH = dh.ID_DH
                         $date_condition
                         GROUP BY s.Ten_SP
                         ORDER BY TongSoLuong DESC";
    $result_best_selling = $conn->query($sql_best_selling);

    $best_selling_data = [];
    if ($result_best_selling->num_rows > 0) {
        while ($row = $result_best_selling->fetch_assoc()) {
            $best_selling_data[] = $row;
        }
    }
    $best_selling_data_json = json_encode($best_selling_data);

    $sql_inventory = "SELECT s.Ten_SP, 
                            --  (s.SoLuongKho - IFNULL(SUM(ctdh.SoLuong), 0)) AS SoLuongTon
                             s.SoLuongKho  AS SoLuongTon
                      FROM san_pham s
                      LEFT JOIN chi_tiet_don_hang ctdh ON s.ID_SP = ctdh.ID_SP
                      GROUP BY s.Ten_SP
                      ORDER BY SoLuongTon DESC";
    $result_inventory = $conn->query($sql_inventory);

    $inventory_data = [];
    if ($result_inventory->num_rows > 0) {
        while ($row = $result_inventory->fetch_assoc()) {
            $inventory_data[] = $row;
        }
    }
    $inventory_data_json = json_encode($inventory_data);

    
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
                <h3>Tổng số khách hàng</h3>
                <p><?php echo number_format($total_cus, 0, ',', '.'); ?></p>
            </div>
            <div class="stat">
                <h3>Tổng số đơn hàng</h3>
                <p><?php echo number_format($total_order, 0, ',', '.'); ?></p>
            </div>
            <div class="stat">
                <h3>Tổng số sản phẩm đã bán</h3>
                <p><?php echo number_format($total_products_sold, 0, ',', '.'); ?></p>
            </div>
            
        </section>
        
        <section class="charts">
            <div class="chart">
                <h3>Doanh thu theo sản phẩm</h3>
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
            <div class="chart">
                <h3>Sản phẩm bán chạy nhất</h3>
                <canvas id="ordersChart" width="400" height="200"></canvas>
            </div>
        </section>
        <section class="charts">
            <div class="chart">
                <h3>Sản phẩm tồn kho</h3>
                <canvas id="inventoryChart" width="400" height="200"></canvas> <!-- Đổi ID thành inventoryChart -->
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
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Truy vấn danh sách đơn hàng từ cơ sở dữ liệu
                $sql = "SELECT sp.*, ctdh.*, bg.Gia, dh.*, SUM(dh.TongTien) AS TongDoanhThu
                        FROM chi_tiet_don_hang ctdh
                        JOIN san_pham sp ON ctdh.ID_SP = sp.ID_SP
                        JOIN don_hang dh ON ctdh.ID_DH = dh.ID_DH
                        JOIN (
                            SELECT ID_SP, Gia
                            FROM co_gia bg1
                            WHERE NgayGio = (
                                SELECT MAX(NgayGio)
                                FROM co_gia bg2
                                WHERE bg1.ID_SP = bg2.ID_SP
                            )
                        ) bg ON sp.ID_SP = bg.ID_SP
                        GROUP BY sp.ID_SP, ctdh.ID_DH, dh.ID_DH, sp.Ten_SP  -- Các trường không phải hàm tổng hợp phải có trong GROUP BY
                        ORDER BY TongDoanhThu DESC
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
                        <td><?php echo number_format($row['DonGia'], 0, '.', ',') . ' VNĐ'; ?></td>
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
        const bestSellingData = <?php echo $best_selling_data_json; ?>;
        const inventoryData = <?php echo $inventory_data_json; ?>;

        // Kiểm tra nếu dữ liệu có thực sự được trả về từ PHP
        console.log(revenueData);
        console.log(bestSellingData);

        // Dữ liệu cho biểu đồ doanh thu theo sản phẩm
        const revenueLabels = revenueData.map(item => item.Ten_SP);
        const revenueValues = revenueData.map(item => parseFloat(item.TongDoanhThu));

        // Dữ liệu cho biểu đồ sản phẩm bán chạy
        const bestSellingLabels = bestSellingData.map(item => item.Ten_SP);
        const bestSellingValues = bestSellingData.map(item => parseInt(item.TongSoLuong));

        // Biểu đồ Doanh thu theo sản phẩm
        const revenueChartCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(revenueChartCtx, {
            type: 'bar',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: revenueValues,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    tooltip: { enabled: true }
                },
                scales: {
                    x: { title: { display: true, text: 'Sản phẩm' } },
                    y: { title: { display: true, text: 'Doanh thu (VNĐ)' } }
                }
            }
        });

        // Biểu đồ Sản phẩm bán chạy
        const bestSellingChartCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(bestSellingChartCtx, {
            type: 'bar', // 'bar' là lựa chọn đúng cho Chart.js v3.x trở lên
            data: {
                labels: bestSellingLabels, // Các nhãn sản phẩm
                datasets: [{
                    label: 'Số lượng bán',
                    data: bestSellingValues, // Dữ liệu số lượng bán
                    backgroundColor: 'rgba(255, 206, 86, 0.6)', // Màu nền của các cột
                    borderColor: 'rgba(255, 206, 86, 1)', // Màu viền của các cột
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }, // Hiển thị legend
                    tooltip: { enabled: true } // Hiển thị tooltip
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Số lượng bán' }, // Tiêu đề trục X
                        beginAtZero: true // Đảm bảo trục X bắt đầu từ 0
                    },
                    y: {
                        title: { display: true, text: 'Sản phẩm' }, // Tiêu đề trục Y
                        beginAtZero: true // Đảm bảo trục Y bắt đầu từ 0
                    }
                }
            }
        });

        // Dữ liệu cho biểu đồ Sản phẩm tồn kho
        const inventoryLabels = inventoryData.map(item => item.Ten_SP);
        const inventoryValues = inventoryData.map(item => parseInt(item.SoLuongTon));

        // Biểu đồ Sản phẩm tồn kho
        const inventoryChartCtx = document.getElementById('inventoryChart').getContext('2d');
        new Chart(inventoryChartCtx, {
            type: 'bar', // Biểu đồ dạng cột dọc
            data: {
                labels: inventoryLabels, // Các nhãn sản phẩm
                datasets: [{
                    label: 'Số lượng tồn kho',
                    data: inventoryValues, // Dữ liệu số lượng tồn kho
                    backgroundColor: 'rgba(75, 192, 192, 0.6)', // Màu nền của các cột
                    borderColor: 'rgba(75, 192, 192, 1)', // Màu viền của các cột
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    tooltip: { enabled: true }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Sản phẩm' } // Tiêu đề trục X
                    },
                    y: {
                        title: { display: true, text: 'Số lượng tồn kho' }, // Tiêu đề trục Y
                        beginAtZero: true // Đảm bảo trục Y bắt đầu từ 0
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