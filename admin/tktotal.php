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
   $start_date = isset($_GET['start-date']) ? date('Y-m-d', strtotime($_GET['start-date'])) : null;
   $end_date = isset($_GET['end-date']) ? date('Y-m-d', strtotime($_GET['end-date'])) : null;
   
   $date_condition = "";
   if ($start_date && $end_date) {
       $date_condition = "AND dh.NgayTao BETWEEN '$start_date' AND '$end_date'";
   }
   
   $sql_revenue = "SELECT 
                       pt.Ten_TT AS PhuongThucThanhToan, 
                       pt.ID_TT, 
                       SUM(dh.TongTien) AS total_revenue
                   FROM 
                      don_hang dh
                   JOIN 
                       phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                   WHERE pt.ID_TT = 1 $date_condition
                   GROUP BY pt.Ten_TT, pt.ID_TT";  
   $result_revenue = $conn->query($sql_revenue);
   $total_revenue = ($result_revenue->num_rows > 0) ? $result_revenue->fetch_assoc()['total_revenue'] : 0;
   

   
    $sql_products = "SELECT 
                       pt.Ten_TT AS PhuongThucThanhToan, 
                       pt.ID_TT, 
                       SUM(dh.TongTien) AS total_products_sold
                   FROM 
                      don_hang dh
                   JOIN 
                       phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                   WHERE pt.ID_TT = 2 $date_condition
                   GROUP BY pt.Ten_TT, pt.ID_TT";
    $result_products = $conn->query($sql_products);
    $total_products_sold = ($result_products->num_rows > 0) ? $result_products->fetch_assoc()['total_products_sold'] : 0;

    $sql_order = "SELECT 
                       pt.Ten_TT AS PhuongThucThanhToan, 
                       pt.ID_TT, 
                       SUM(dh.TongTien) AS total_order
                   FROM 
                      don_hang dh
                   JOIN 
                       phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                   WHERE pt.ID_TT = 3 $date_condition
                   GROUP BY pt.Ten_TT, pt.ID_TT"; 
    $result_order = $conn->query($sql_order);
    $total_order = ($result_order->num_rows > 0) ? $result_order->fetch_assoc()['total_order'] : 0;

    $sql_cus = "SELECT 
                       pt.Ten_TT AS PhuongThucThanhToan, 
                       pt.ID_TT, 
                       SUM(dh.TongTien) AS total_cus
                   FROM 
                      don_hang dh
                   JOIN 
                       phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                   WHERE pt.ID_TT = 4 $date_condition
                   GROUP BY pt.Ten_TT, pt.ID_TT"; 
    $result_cus = $conn->query($sql_cus);
    $total_cus = ($result_cus->num_rows > 0) ? $result_cus->fetch_assoc()['total_cus'] : 0;

    $sql_zalo = "SELECT 
                       pt.Ten_TT AS PhuongThucThanhToan, 
                       pt.ID_TT, 
                       SUM(dh.TongTien) AS total_zalo
                   FROM 
                      don_hang dh
                   JOIN 
                       phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
                   WHERE pt.ID_TT = 5 $date_condition
                   GROUP BY pt.Ten_TT, pt.ID_TT"; 
    $result_zalo = $conn->query($sql_zalo);
    $total_zalo = ($result_zalo->num_rows > 0) ? $result_zalo->fetch_assoc()['total_zalo'] : 0;

    
    $sql_revenue = "SELECT 
                        pt.Ten_TT AS PhuongThucThanhToan, 
                        SUM(dh.TongTien) AS TongDoanhThu
                    FROM 
                       don_hang dh
                    JOIN 
                        phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT
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
    <h2>Danh sách đơn hàng</h2>
    <table>
        <thead>
            <tr>
                <th>Đơn hàng</th>
                <th>Ngày đặt hàng</th>
                <th>Tổng tiền</th>
                <th>Phương thức thanh toán</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT 
                            dh.ID_DH, 
                            dh.NgayTao, 
                            dh.TongTien AS TongDoanhThu, 
                            pt.Ten_TT 
                        FROM 
                            don_hang dh
                        JOIN 
                            phuong_thuc_thanh_toan pt ON dh.ID_TT = pt.ID_TT;
                        ";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID_DH']); ?></td>
                        <td><?php echo htmlspecialchars($row['NgayTao']); ?></td>                
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

        const labels = revenueData.map(item => item.PhuongThucThanhToan);  
        const data = revenueData.map(item => parseFloat(item.TongDoanhThu));  

        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line', 
            data: {
                labels: labels, 
                datasets: [{
                    label: 'Doanh thu (VND)', 
                    data: data, 
                    backgroundColor: 'rgba(75, 192, 192, 0.5)', 
                    borderColor: 'rgba(75, 192, 192, 1)', 
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