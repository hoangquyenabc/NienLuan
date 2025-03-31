<?php
    session_start();
    require('includes/header.php');
    require_once "connect.php";
 ?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Khách hàng
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
                    <li><a class="dropdown-item" href="editstaff.php">Thông tin</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../dangxuat.php">Đăng xuất</a></li>
                </ul>
                
            </div>
            
        <?php endif; ?>

</div>

    <div class="container-sp" style="margin-top: 100px;">
        <?php
            if (isset($_POST["tim"])) {
                $tim = trim($_POST["timkiem"]); 
                if ($tim == "") {
                    echo "<p style='color:red;'>Vui lòng nhập từ khóa tìm kiếm</p>";
                } else {
                    $sql_tr = "SELECT k.*, h.ID_DH, d.ID_DC, d.* 
                                FROM khach_hang k
                                JOIN don_hang h ON k.ID_KH = h.ID_KH 
                                JOIN dia_chi_giao_hang d ON h.ID_DH = d.ID_DH 
                                WHERE h.ID_DH = (
                                    SELECT MIN(h1.ID_DH)
                                    FROM don_hang h1
                                    WHERE h1.ID_KH = k.ID_KH
                                )
                                AND k.Ten_KH LIKE '%$tim%' 
                                ORDER BY k.ID_KH";
                }
            } else {
                $sql_tr = "SELECT k.*, h.ID_DH, d.ID_DC, d.* 
                            FROM khach_hang k
                            JOIN don_hang h ON k.ID_KH = h.ID_KH 
                            JOIN dia_chi_giao_hang d ON h.ID_DH = d.ID_DH 
                            WHERE h.ID_DH = (
                                SELECT MIN(h1.ID_DH)
                                FROM don_hang h1
                                WHERE h1.ID_KH = k.ID_KH
                            )
                            ORDER BY k.ID_KH";
            }

            $result = mysqli_query($conn, $sql_tr);
        ?>
    
    <div class="search-bar">
        <form action="" method="POST" onsubmit="return validateForm()">
            <div class="search-bar">
                <input type="text" id="timkiem-kh" name="timkiem" placeholder="Tìm kiếm...">
                <button type="submit" class="tim" name="tim">Tìm</button>
            </div>
        </form>
    </div>
        <div class="product-list" >
            <div class="header">Danh sách khách hàng</div>
            <table  style="margin-left: 1px;">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <?php
                    while ($row = mysqli_fetch_assoc($result)){
                ?>  
                <tr>
                    <td><?=$row['ID_KH']?></td>
                    <td><?=$row['Ho_KH']?></td>
                    <td name="tdten"><?=$row['Ten_KH']?></td>                       
                    <td><?=$row['Email_KH']?></td>
                    <td><?=$row['sdt']?></td>
                    <td>
                        <?=$row['SoNha']?>, <?=$row['XaPhuong']?>, <?=$row['QuanHuyen']?>, <?=$row['TinhTP']?>
                    </td>
                </tr>
                <?php 
                    }  
                ?>  
            </tbody>
            </table>
        </div>
    </div>

<script>
    function validateForm() {
        const input = document.getElementById("timkiem-kh").value.trim(); 
        if (input === "") {
            alert("Vui lòng nhập từ khóa trước khi tìm kiếm!");
            return false; 
        }
        return true; 
    }
</script>
