<?php 
    session_start();
    require('includes/header.php');
    require_once "connect.php";
    
?>
<div class="top-bar">
        <div class="breadcrumb">
            Pages / Chỉnh sửa sản phẩm
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
    $id = $_GET['id']; // Lấy ID sản phẩm từ URL

    // Lấy thông tin sản phẩm cần cập nhật
    $sql_tr = "SELECT sp.*, bg.*, l.*, t.*
               FROM san_pham sp
               JOIN (
                   SELECT ID_SP, Gia, NgayGio
                   FROM co_gia bg1
                   WHERE NgayGio = (
                       SELECT MAX(NgayGio)
                       FROM co_gia bg2
                       WHERE bg1.ID_SP = bg2.ID_SP
                   )
               ) bg ON sp.ID_SP = bg.ID_SP
               JOIN loai_sp l ON l.ID_L = sp.ID_L
               JOIN thuong_hieu t ON t.ID_TH = sp.ID_TH
               WHERE sp.ID_SP = '$id';"; // Lọc theo ID sản phẩm cần cập nhật
    $result = mysqli_query($conn, $sql_tr);
    $row_up = mysqli_fetch_assoc($result);

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
        if ($_FILES['image']['name'] == '') {
            $image = $row_up['HinhAnh'];  // Giữ lại tên ảnh cũ từ cơ sở dữ liệu nếu không thay đổi
        } else {
            $image = $_FILES['image']['name'];  // Lấy tên ảnh mới người dùng tải lên
            $image_tmp = $_FILES['image']['tmp_name'];
            move_uploaded_file($image_tmp, '../images/' . $image);  // Di chuyển ảnh mới vào thư mục
        }

        // Cập nhật thông tin sản phẩm
        $sql = "UPDATE san_pham 
                SET ID_L = '$category', ID_TH = '$brand', Ten_SP = '$name', MoTa = '$description', HinhAnh = '$image', KichThuoc = '$length', ChatLieu = '$material'
                WHERE ID_SP = '$id'";
        $query = mysqli_query($conn, $sql);

        // Cập nhật giá mới
        $current_time = date('Y-m-d H:i:s'); 
        // Thêm bản ghi thời gian vào bảng thoi_diem
        $sql_time = "INSERT INTO thoi_diem (NgayGio) VALUES ('$current_time')";
        $time_query = mysqli_query($conn, $sql_time);

        // Lấy ID của bản ghi thời gian vừa thêm
        $time_id = mysqli_insert_id($conn);

        // Cập nhật giá vào bảng co_gia
        $sql_price = "INSERT INTO co_gia (NgayGio, ID_SP, Gia) 
                      VALUES ('$current_time', '$id', '$price')";
        $price_query = mysqli_query($conn, $sql_price);

        // Di chuyển file ảnh vào thư mục đích nếu mọi thứ thành công
        if ($query && $time_query && $price_query) {
            echo "<script>
                            alert('Cập nhật sản phẩm $name thành công!');
                            window.location.href = 'products.php';  
                          </script>";
        } else {
            echo "<p>Lỗi khi cập nhật sản phẩm: " . mysqli_error($conn) . "</p>";
        }
    }
?>
<div>
    <h2 style="text-align: center; margin: 20px 0; font-size: 34px; color:#e91e63; margin-bottom: 20px;">Chỉnh sửa sản phẩm</h2>
    <form method="POST" enctype="multipart/form-data">
        <!-- Loại sản phẩm và Thương hiệu -->
        <div class="form-row">
            <div class="form-group">
                <label for="category">Loại sản phẩm</label>
                <select id="category" name="category" required>
                    <option value="">-Chọn-</option>
                    <?php
                        // Hiển thị danh sách loại sản phẩm và chọn lựa loại hiện tại
                        while ($row = mysqli_fetch_assoc($category_result)) {
                            $selected = ($row['ID_L'] == $row_up['ID_L']) ? 'selected' : ''; // Chọn loại sản phẩm hiện tại
                            echo "<option value='" . $row['ID_L'] . "' $selected>" . $row['Ten_L'] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="brand">Thương hiệu</label>
                <select id="brand" name="brand" required>
                    <option value="">-Chọn-</option>
                    <?php
                        // Hiển thị danh sách thương hiệu và chọn lựa thương hiệu hiện tại
                        while ($row = mysqli_fetch_assoc($brand_result)) {
                            $selected = ($row['ID_TH'] == $row_up['ID_TH']) ? 'selected' : ''; // Chọn thương hiệu hiện tại
                            echo "<option value='" . $row['ID_TH'] . "' $selected>" . $row['Ten_TH'] . "</option>";
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
                <input type="text" id="name" name="name" value="<?php echo $row_up['Ten_SP']; ?>" placeholder="Nhập tên sản phẩm" required>
            </div>
            <div class="form-group">
                <label for="price">Giá bán (VND)</label>
                <input type="number" id="price" name="price" value="<?php echo $row_up['Gia']; ?>" placeholder="Nhập giá bán sản phẩm" min="1" required>
            </div>
        </div>

        <!-- Kích thước và Chất liệu -->
        <div class="form-row">
            <div class="form-group">
                <label for="length">Kích thước</label>
                <input type="text" id="length" name="length" value="<?php echo $row_up['KichThuoc']; ?>" placeholder="Nhập kích thước sản phẩm" required>
            </div>
            <div class="form-group">
                <label for="material">Chất liệu</label>
                <input type="text" id="material" name="material" value="<?php echo $row_up['ChatLieu']; ?>" placeholder="Nhập chất liệu sản phẩm" required>
            </div>
        </div>

        <!-- Mô tả -->
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" placeholder="Nhập mô tả sản phẩm" required><?php echo $row_up['MoTa']; ?></textarea>
        </div>

        <!-- Tải ảnh -->
        <div class="form-group">
            <label for="image">Tải ảnh sản phẩm:</label>
            <input type="file" id="image" name="image">
            <!-- Hiển thị ảnh hiện tại nếu có -->
            <img src="../images/<?php echo $row_up['HinhAnh']; ?>" alt="Ảnh sản phẩm hiện tại" width="100">
        </div>

        <!-- Nút Cập nhật -->
        <button type="submit" class="submit-btn" name="addsp">Cập nhật</button>
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
