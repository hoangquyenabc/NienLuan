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
            <a 
                href="#" 
                class="custom-dropdown-toggle" 
                id="userDropdown" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
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
<?php
        $start_date = isset($_GET['start-date']) ? $_GET['start-date'] : null;
        $end_date = isset($_GET['end-date']) ? $_GET['end-date'] : null;

        $date_condition = "";
        if ($start_date && $end_date) {
            $date_condition = "WHERE NgayTao BETWEEN '$start_date' AND '$end_date'";
        }

        $sql_sales_by_date = "SELECT DATE(NgayTao) AS sale_date, SUM(TongTien) AS total_sales 
            FROM don_hang 
            $date_condition
            GROUP BY DATE(NgayTao)"; 
        $result_sales_by_date = $conn->query($sql_sales_by_date);

        $sales_data = [];
        while ($row = $result_sales_by_date->fetch_assoc()) {
            $sales_data[] = [
                'date' => $row['sale_date'],
                'total_sales' => $row['total_sales']
            ];
        }

        $sql_orders_by_date = "SELECT DATE(NgayTao) AS order_date, COUNT(ID_DH) AS total_orders 
            FROM don_hang 
            $date_condition
            GROUP BY DATE(NgayTao)";
        $result_orders_by_date = $conn->query($sql_orders_by_date);

        $orders_data = [];
        while ($row = $result_orders_by_date->fetch_assoc()) {
            $orders_data[] = [
                'date' => $row['order_date'],
                'total_orders' => $row['total_orders']
            ];
        }

        $sql_revenue = "SELECT SUM(TongTien) AS total_revenue FROM don_hang $date_condition";
        $result_revenue = $conn->query($sql_revenue);
        $total_revenue = ($result_revenue->num_rows > 0) ? $result_revenue->fetch_assoc()['total_revenue'] : 0;

        $sql_products = "SELECT SUM(SoLuong) AS total_products_sold FROM chi_tiet_don_hang
                        JOIN don_hang ON chi_tiet_don_hang.ID_DH = don_hang.ID_DH
                        $date_condition"; 
        $result_products = $conn->query($sql_products);
        $total_products_sold = ($result_products->num_rows > 0) ? $result_products->fetch_assoc()['total_products_sold'] : 0;

        $sql_order = "SELECT COUNT(ID_DH) AS total_order FROM don_hang $date_condition";
        $result_order = $conn->query($sql_order);
        $total_order = ($result_order->num_rows > 0) ? $result_order->fetch_assoc()['total_order'] : 0;

        $sql_cus = "SELECT COUNT(DISTINCT ID_KH) AS total_cus FROM don_hang $date_condition";
        $result_cus = $conn->query($sql_cus);
        $total_cus = ($result_cus->num_rows > 0) ? $result_cus->fetch_assoc()['total_cus'] : 0;
      
        $sales_data_json = json_encode($sales_data); 
        $orders_data_json = json_encode($orders_data);
?>

<div class="container-sp" style="margin-top: 100px;">
<div class="dashboard">
        <header>
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
                <h3>Tổng doanh thu</h3>
                <p><?php echo number_format($total_revenue, 0, '.', ','); ?> VNĐ</p>
            </div>
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
                <h3>Doanh số bán hàng</h3>
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
            <div class="chart">
                <h3>Đơn hàng</h3>
                <canvas id="ordersChart" width="400" height="200"></canvas>
            </div>
        </section>
    </div>
    <div class="order-list">
    <h2>Danh Sách Đơn Hàng</h2>
    <table>
        <thead>
            <tr>
                <th>Đơn Hàng</th>
                <th>Khách Hàng</th>
                <th>Ngày Đặt</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT d.*, p.*, k.* 
                        FROM don_hang d
                        JOIN phuong_thuc_thanh_toan p ON p.ID_TT = d.ID_TT
                        JOIN khach_hang k ON k.ID_KH = d.ID_KH
                        ORDER BY NgayTao
                       ;";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID_DH']); ?></td>
                        <td><?php echo htmlspecialchars($row['Ho_KH']) . ' ' . htmlspecialchars($row['Ten_KH']); ?></td>
                        <td><?php echo htmlspecialchars($row['NgayTao']); ?></td>
                        <td><?php echo number_format($row['TongTien'], 0, '.', ',') ?> VNĐ</td>
                        <td><?php echo htmlspecialchars($row['TrangThai_DH']); ?></td>
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
        const salesData = <?php echo $sales_data_json; ?>; 
        const ordersData = <?php echo $orders_data_json; ?>;

        const salesLabels = salesData.map(item => item.date); 
        const salesValues = salesData.map(item => item.total_sales); 

        const ordersLabels = ordersData.map(item => item.date);
        const ordersValues = ordersData.map(item => item.total_orders);

        const salesChartCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesChartCtx, {
            type: 'line', 
            data: {
                labels: salesLabels, 
                datasets: [{
                    label: 'Doanh số bán hàng (VNĐ)',
                    data: salesValues, 
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4 
                }]
            },
            options: {
                responsive: true, 
                plugins: {
                    legend: { display: true }, 
                    tooltip: { enabled: true } 
                },
                scales: {
                    x: { title: { display: true, text: 'Ngày' } },
                    y: { title: { display: true, text: 'Doanh số (VNĐ)' } }
                }
            }
        });

        const ordersChartCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersChartCtx, {
            type: 'bar',
            data: {
                labels: ordersLabels,
                datasets: [{
                    label: 'Số lượng đơn hàng',
                    data: ordersValues,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
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
                    x: { title: { display: true, text: 'Ngày' } },
                    y: { title: { display: true, text: 'Số lượng đơn hàng' } }
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