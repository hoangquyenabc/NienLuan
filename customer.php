<?php 
    
    require('header.php'); 
    require_once "connect.php";

?>


    <div class="container-sp"  style="margin-left: 250px;">
        <?php
            $sql_tr = "SELECT * FROM khach_hang 
                       ;";
            $result = mysqli_query($conn, $sql_tr);
        ?>
        <?php
            if (!isset($_SESSION['ID_KH'])) {
            die("Bạn cần đăng nhập để xem thông tin.");
            }

            $current_user_id = $_SESSION['ID_KH'];

            $sql = "SELECT d.*, h.*, k.*
                    FROM dia_chi_giao_hang d
                    JOIN don_hang h ON d.ID_DH = h.ID_DH
                    JOIN khach_hang k ON h.ID_KH = k.ID_KH
                    WHERE k.ID_KH = ?";
            
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $current_user_id); 
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) { 

        ?>

       
        <div class="product-list">
            <div class="header" style="color: #7fad39">Thông tin khách hàng</div>
            <table  style="margin-left: 1px;">
                <thead>
                    <tr>
                        
                        <th>Họ</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ngày đăng ký</th>
                        
                        <th>Hành động</th>
                        
                    </tr>
                </thead>
                <tbody id="productTableBody">
                     
                    <tr>
                        
                        <td><?=$row['Ho_KH']?></td>
                        <td name="tdten"><?=$row['Ten_KH']?></td>                       
                        <td><?=$row['Email_KH']?></td>
                        <td><?=$row['sdt']?></td>
                        <?php
                            $employee_address = $row['SoNha'] . ', ' . $row['XaPhuong'] . ', ' . $row['QuanHuyen'] . ', ' . $row['TinhTP'];
                        ?>
                        <td><?= htmlspecialchars($employee_address) ?></td>

                        <td><?=$row['NgayDangKy']?></td>
                        
                        
                        <td>
                        <a href="editcustomer.php?id=<?=$row['ID_KH']?>">
                            <button class="action-btn edit-btn" style="font-size: 16px;">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                        </a>
                        
                        </td>                        
                    </tr>
                      
                </tbody>
            </table>
        </div>
        <?php
            } else {
                echo "Không tìm thấy thông tin.";
            }
        ?>
    </div>

<?php 
    require('footer.php'); 

?> 
