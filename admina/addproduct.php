<?php 
    session_start();
    require('includes/header.php');
    require_once "connect.php";
    
?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Thêm mới sản phẩm
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

<div class="container-editproduct">
<?php
    // Lấy danh sách loại sản phẩm
    $category_query = "SELECT ID_L, Ten_L FROM loai_sp";
    $category_result = mysqli_query($conn, $category_query);

    // Lấy danh sách thương hiệu
    $brand_query = "SELECT ID_TH, Ten_TH FROM thuong_hieu";
    $brand_result = mysqli_query($conn, $brand_query);

    // Xử lý form khi gửi
    if (isset($_POST['addsp'])) {
        $category = $_POST['category'];
        $brand = $_POST['brand'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $length = $_POST['length'];
        $material = $_POST['material'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];

        // Thêm sản phẩm vào cơ sở dữ liệu
        $sql = "INSERT INTO san_pham (ID_L, ID_TH, Ten_SP, MoTa, HinhAnh, KichThuoc, ChatLieu) 
                VALUES ('$category', '$brand', '$name', '$description', '$image', '$length', '$material')";
        $query = mysqli_query($conn, $sql);

        // Lấy ID sản phẩm vừa thêm
        $last_id = mysqli_insert_id($conn);

        // Lấy thời gian hiện tại
        $current_time = date('Y-m-d H:i:s'); 
        // Thêm bản ghi thời gian vào bảng thoi_diem
        $sql_time = "INSERT INTO thoi_diem (NgayGio) VALUES ('$current_time')";
        $time_query = mysqli_query($conn, $sql_time);

        // Lấy ID của bản ghi thời gian vừa thêm
        $time_id = mysqli_insert_id($conn);

        // Thêm giá vào bảng co_gia với ID_SP và NgayGio
        $sql_price = "INSERT INTO co_gia (NgayGio, ID_SP, Gia) 
                      VALUES ('$current_time', '$last_id', '$price')";
        $price_query = mysqli_query($conn, $sql_price);

        // Di chuyển file ảnh vào thư mục đích
        if ($query && $time_query && $price_query) {
            move_uploaded_file($image_tmp, '../images/' . $image);
            echo "<script>
                    alert('Sản phẩm đã được thêm thành công!');
                    window.location.href = 'products.php'; // Điều hướng lại trang danh mục
                    </script>";
        } else {
            echo "<p>Lỗi khi thêm sản phẩm: " . mysqli_error($conn) . "</p>";
        }
    }
?>


    <h2 style="text-align: center; margin: 20px 0; font-size: 34px; color:#e91e63; margin-bottom: 20px;">Thêm sản phẩm mới</h2>
    <form method="POST" enctype="multipart/form-data">
        <!-- Loại sản phẩm và Thương hiệu -->
        <div class="form-row">
            <div class="form-group">
                <label for="category">Loại sản phẩm</label>
                <select id="category" name="category" required>
                    <option value="">-Chọn-</option>
                    <?php
                        // Hiển thị danh sách loại sản phẩm
                        while ($row = mysqli_fetch_assoc($category_result)) {
                            echo "<option value='" . $row['ID_L'] . "'>" . $row['Ten_L'] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="brand">Thương hiệu</label>
                <select id="brand" name="brand" required>
                    <option value="">-Chọn-</option>
                    <?php
                        // Hiển thị danh sách thương hiệu
                        while ($row = mysqli_fetch_assoc($brand_result)) {
                            echo "<option value='" . $row['ID_TH'] . "'>" . $row['Ten_TH'] . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>

        <!-- Các phần còn lại của form như trước -->
        <!-- Tên sản phẩm và Giá bán -->
        <div class="form-row">
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" id="name" name="name" placeholder="Nhập tên sản phẩm" required>
            </div>
            <div class="form-group">
                <label for="price">Giá bán (VND)</label>
                <input type="number" id="price" name="price" placeholder="Nhập giá bán sản phẩm" min="1" required>
            </div>

        </div>

        <!-- Kích thước và Chất liệu -->
        <div class="form-row">
            <div class="form-group">
                <label for="length">Kích thước</label>
                <input type="text" id="length" name="length" placeholder="Nhập kích thước sản phẩm" required>
            </div>
            <div class="form-group">
                <label for="material">Chất liệu</label>
                <input type="text" id="material" name="material" placeholder="Nhập chất liệu sản phẩm" required>
            </div>
        </div>

        <!-- Mô tả -->
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" placeholder="Nhập mô tả sản phẩm" required></textarea>
        </div>

        <!-- Tải ảnh -->
        <div class="form-group">
            <label for="image">Tải ảnh sản phẩm:</label>
            <input type="file" id="image" name="image" required>
        </div>

        <!-- Nút Thêm -->
        <button type="submit" class="submit-btn" name="addsp">Thêm</button>
    </form>
</div>


<script>
       


        
        function Delete(name){
            const confirmDelete = confirm(`Bạn có chắc muốn xóa sản phẩm: ${name}?`);
            if (confirmDelete) {
                alert(`Sản phẩm "${name}" đã được xóa.`);

        
                setTimeout(function() {
                    window.location.href = 'products.php'; 
                }, 1000); 
            }
        }
    </script>

</body>
</html>
