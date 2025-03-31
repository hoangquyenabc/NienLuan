<?php 
    session_start();
    require('includes/header.php'); 
    require_once "connect.php";
    
?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Danh mục
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

<div class="container-category">

        <div class="product-list-dm">

            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Tìm kiếm...">
                <button onclick="searchProduct()" class="tim">Tìm</button>
        </div>
        
        <?php 
           
            $sql_str = "SELECT * FROM loai_sp ORDER BY ID_L";
            $result = mysqli_query($conn, $sql_str);
        ?>

            <div class="header">Danh sách danh mục</div>
            <table style="margin-left: 1px;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên danh mục</th>
                        <th>Hình ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">

                    <?php
                    while ($row = mysqli_fetch_assoc($result)){
                    ?>    
                    <tr>
                        <td><?=$row['ID_L']?></td>
                        <td><?=$row['Ten_L']?></td>
                        <td>
                        <?php
                            $file = $row["HinhAnh_L"];
                            $avatar_url = "images/anhdanhmuc/" . $file;
                            echo "<img src='{$avatar_url}' style='width: 50px; height: auto;'>";
                        ?> 
                        </td>
                        <td>
                        <a href="editcategory.php?id=<?=$row['ID_L']?>">
                            <button class="action-btn edit-btn" style="font-size: 16px;">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                        </a>
                        <a onclick="return Delete('<?php echo $row['Ten_L'];?>')" href="deletecategory.php?id=<?=$row['ID_L']?>">
                            <button class="action-btn delete-btn" style="font-size: 16px;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </a>
                        </td>    
                    </tr> 
                    <?php 
                    }  
                    ?>      

                </tbody>
            </table>
            </div>

                  

        <div class="container-dm">

        <?php
            $id = $_GET['id'];
            $sql_up = "SELECT * FROM loai_sp WHERE ID_L = $id";
            $query_up = mysqli_query($conn, $sql_up);
            $row_up = mysqli_fetch_assoc($query_up);
            if(isset($_POST['btnupdate'])){
                $name = $_POST['name'];
                if($_FILES['image']['name'] == ''){
                    $image = $row_up['HinhAnh_L'];  // Giữ lại tên ảnh cũ từ cơ sở dữ liệu
                } else {
                    $image = $_FILES['image']['name'];  // Lấy tên ảnh mới người dùng tải lên
                    $image_tmp = $_FILES['image']['tmp_name'];
                    move_uploaded_file($image_tmp, 'images/anhdanhmuc/' . $image);  // Di chuyển ảnh mới vào thư mục
                }
                $name = $_POST['name'];
                $sql = "UPDATE loai_sp SET Ten_L = '$name', HinhAnh_L = '$image' WHERE ID_L = $id";
                $query = mysqli_query($conn,$sql);
                if ($query) {
                    // Nếu cập nhật thành công, hiển thị thông báo và chuyển hướng
                    echo "<script>
                            alert('Cập nhật danh mục $name thành công!');
                            window.location.href = 'category.php';  // Chuyển hướng đến trang category.php
                          </script>";
                } else {
                    // Nếu có lỗi trong quá trình cập nhật
                    echo "<script>
                            alert('Có lỗi xảy ra khi cập nhật!');
                          </script>";
                }
            }  
        ?>
        
            <h2>Chỉnh sửa danh mục</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Tên danh mục</label>
                    <input type="text" id="name" name="name" placeholder="Nhập tên sản phẩm" required value="<?php echo $row_up['Ten_L']; ?>">
                </div>
                
                <div class="form-group">
                    <label for="image">Tải ảnh danh mục:</label>
                    <img src="images/anhdanhmuc/<?php echo $row_up['HinhAnh_L']; ?>" style='width: 100px; height: auto;'>
                   
                    <input type="file" id="image" name="image">
                </div>
                <button type="submit" class="submit-btn" name="btnupdate" >Cập nhật</button>
            </form>
        </div>
    
 

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
    </script>

</body>
</html>    


    