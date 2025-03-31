<?php 
    session_start();
    require('includes/header.php'); 
    require_once "connect.php";
    
?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Sửa đánh giá
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

<div class="container-category">
        <div class="product-list-dm" style="width: 90%;">
        <?php
    if (isset($_POST["tim"])) {
        $tim = trim($_POST["timkiem"]);
        if ($tim == "") {
            echo "<p style='color:red;'>Vui lòng nhập từ khóa tìm kiếm</p>";
        } else {
            $sql_tr = "SELECT d.*, k.*, s.* 
                    FROM danh_gia d 
                    JOIN khach_hang k ON d.ID_KH = k.ID_KH
                    JOIN san_pham s ON s.ID_SP = d.ID_SP
                    WHERE s.Ten_SP LIKE '%$tim%' 
                    ORDER BY s.Ten_SP;";
        }
    } else {
        $sql_tr = "SELECT d.*, k.*, s.* 
                    FROM danh_gia d 
                    JOIN khach_hang k ON d.ID_KH = k.ID_KH
                    JOIN san_pham s ON s.ID_SP = d.ID_SP;";
    }

    $result = mysqli_query($conn, $sql_tr);
?>


    
    <div class="search-bar">
        <form action="" method="POST" onsubmit="return validateForm()">
            <div class="search-bar">
                <input type="text" id="timkiem-dg" name="timkiem" placeholder="Tìm kiếm...">
                <button type="submit" class="tim" name="tim">Tìm</button>
            </div>
        </form>
    </div>

            <div class="header">Danh sách đánh giá</div>
            <table style="margin-left: 1px;">
                <thead>
                    <tr>
                    <!-- <th>Khách hàng</th> -->
                    <!-- <th>Sản phẩm</th> -->
                    
                    <th>Nội dung</th>
                    <th>Rating</th>
                    <th>Ngày đăng</th>
                            
                    </tr>
                </thead>
                <tbody id="productTableBody">

                    <?php
                    while ($row = mysqli_fetch_assoc($result)){
                    ?>    
                    <tr>
                        <!-- <td><?php echo htmlspecialchars($row['Ho_KH']) . ' ' . htmlspecialchars($row['Ten_KH']); ?></td> -->
                        <!-- <td><?=$row['Ten_SP']?></td> -->
                                           
                        <td style="text-align: left;"><?=$row['NoiDung']?></td>
                        <td><?=$row['Rating']?></td>
                        <td><?=$row['Created_at']?></td>
                        </tr> 
                    <?php 
                    }  
                    ?>      

                </tbody>
            </table>
            </div>

                  

        <div class="container-dm" style="margin-left: 10px; max-width: 400px;">

        <?php
            $id = $_GET['id'];
            $sql_up = "SELECT * FROM danh_gia WHERE ID_SP = ?";
            $stmt_up = $conn->prepare($sql_up);
            $stmt_up->bind_param("i", $id);
            $stmt_up->execute();
            $result_up = $stmt_up->get_result();
            $row_up = $result_up->fetch_assoc();

            if (isset($_POST['btnupdate'])) {

                $name = $_POST['name'];
                $rating = $_POST['rating'];
                $created_at = $_POST['created_at'];

                $sql_update = "UPDATE danh_gia SET NoiDung = ?, Rating = ?, Created_at = ? WHERE ID_SP = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("sisi", $name, $rating, $created_at, $id);

                if ($stmt_update->execute()) {
                    echo "<script>
                            alert('Cập nhật đánh giá thành công!');
                            window.location.href = 'rating.php';  // Chuyển hướng đến trang category.php
                        </script>";
                } else {
                    echo "<script>
                            alert('Có lỗi xảy ra khi cập nhật!');
                        </script>";
                }
            }  
        ?>

    <h2>Chỉnh sửa</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nội dung</label>
            <input type="text" id="name" name="name" placeholder="Nhập nội dung đánh giá" required value="<?php echo htmlspecialchars($row_up['NoiDung']); ?>">
        </div>
        <div class="form-group">
            <label for="rating">Rating</label>
            <input type="number" id="rating" name="rating" placeholder="Chọn rating" required value="<?php echo $row_up['Rating']; ?>" min="1" max="5">
        </div>
        <div class="form-group">
            <label for="created_at">Ngày đăng</label>
            <input type="date" id="created_at" name="created_at" required value="<?php echo $row_up['Created_at']; ?>">
        </div>
        
        <button type="submit" class="submit-btn" name="btnupdate" style="width: 30%;">Cập nhật</button>
    </form>
    
 

    </div>

    <script>
        

        
        function Delete(name){
            const confirmDelete = confirm(`Bạn có chắc muốn xóa sản phẩm: ${name}?`);
            if (confirmDelete) {
                alert(`Sản phẩm "${name}" đã được xóa.`);

        
                setTimeout(function() {
                    window.location.href = 'category.php'; 
                }, 1000); 
            }
        }
        function validateForm() {
            const input = document.getElementById("timkiem-dg").value.trim(); 
            if (input === "") {
                alert("Vui lòng nhập từ khóa trước khi tìm kiếm!");
                return false; 
            }
            return true; 
        }
    </script>

</body>
</html>    


    