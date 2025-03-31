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

<div class="container-editproduct">
<?php
    $id = $_GET['id'];
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
               WHERE sp.ID_SP = '$id';";
    $result = mysqli_query($conn, $sql_tr);
    $row_up = mysqli_fetch_assoc($result);
    $category_query = "SELECT * FROM loai_sp";
    $category_result = mysqli_query($conn, $category_query);
    $brand_query = "SELECT * FROM thuong_hieu";
    $brand_result = mysqli_query($conn, $brand_query);

    if (isset($_POST['addsp'])) {
        $category = $_POST['category'];
        $brand = $_POST['brand'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $length = $_POST['length'];
        $status = $_POST['status'];
        $material = $_POST['material'];
        $description = $_POST['description'];
        if (!empty($_FILES['mainImage']['name'])) {
            $mainImage = basename($_FILES['mainImage']['name']); 
            $mainImage_tmp = $_FILES['mainImage']['tmp_name'];
        
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $image_ext = pathinfo($mainImage, PATHINFO_EXTENSION);
        
            if (in_array(strtolower($image_ext), $allowed_types)) {
                if (move_uploaded_file($mainImage_tmp, '../images/' . $mainImage)) {
                }
            }
        } else {
            $mainImage = $row_up['HinhAnh']; 
        }
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif']; 
        $descImages = [];
        if (isset($_FILES['descImages']) && !empty($_FILES['descImages']['name'][0])) {
            foreach ($_FILES['descImages']['name'] as $key => $descImage) {
                $descImage = basename($descImage);
                $descTmp = $_FILES['descImages']['tmp_name'][$key];

                $desc_image_ext = pathinfo($descImage, PATHINFO_EXTENSION);
                if (in_array(strtolower($desc_image_ext), $allowed_types)) {
                    if (move_uploaded_file($descTmp, '../images/' . $descImage)) {
                        $descImages[] = $descImage;
                    }
                }
            }
            $descImagesStr = implode(";", $descImages); 
        } else {
            $descImagesStr = $row_up['Hinh']; 
        }
        
        $sql = "UPDATE san_pham 
                SET ID_L = '$category', ID_TH = '$brand', Ten_SP = '$name', MoTa = '$description', 
                    HinhAnh = '$mainImage', KichThuoc = '$length', ChatLieu = '$material', Hinh = '$descImagesStr', TrangThai_SP = '$status'
                WHERE ID_SP = '$id'";

        $query = mysqli_query($conn, $sql);

        $current_time = date('Y-m-d H:i:s'); 
        $sql_time = "INSERT INTO thoi_diem (NgayGio) VALUES ('$current_time')";
        $time_query = mysqli_query($conn, $sql_time);

        $time_id = mysqli_insert_id($conn);

        $sql_price = "INSERT INTO co_gia (NgayGio, ID_SP, Gia) 
                      VALUES ('$current_time', '$id', '$price')";
        $price_query = mysqli_query($conn, $sql_price);
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
        <div class="form-row">
            <div class="form-group">
                <label for="category">Loại sản phẩm</label>
                <select id="category" name="category" required>
                    <option value="">-Chọn-</option>
                    <?php
                        while ($row = mysqli_fetch_assoc($category_result)) {
                            if ($row['TrangThai_L'] == 'active') {  
                                $selected = ($row['ID_L'] == $row_up['ID_L']) ? 'selected' : ''; 
                                echo "<option value='" . $row['ID_L'] . "' $selected>" . $row['Ten_L'] . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="brand">Thương hiệu</label>
                <select id="brand" name="brand" required>
                    <option value="">-Chọn-</option>
                    <?php
                        while ($row = mysqli_fetch_assoc($brand_result)) {
                            $selected = ($row['ID_TH'] == $row_up['ID_TH']) ? 'selected' : ''; 
                            echo "<option value='" . $row['ID_TH'] . "' $selected>" . $row['Ten_TH'] . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
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
        <div class="form-row">
            <div class="form-group">
                <label for="length">Kích thước</label>
                <input type="text" id="length" name="length" value="<?php echo $row_up['KichThuoc']; ?>" placeholder="Nhập kích thước sản phẩm" required>
            </div>
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select id="status" name="status" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="inactive" <?php echo ($row_up['TrangThai_SP'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    <option value="active" <?php echo ($row_up['TrangThai_SP'] === 'active') ? 'selected' : ''; ?>>Active</option>
                </select>
            </div>
        </div>
        <div class="form-group">
                <label for="material">Chất liệu</label>
                <input type="text" id="material" name="material" value="<?php echo $row_up['ChatLieu']; ?>" placeholder="Nhập chất liệu sản phẩm" required>
            </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" placeholder="Nhập mô tả sản phẩm" required><?php echo $row_up['MoTa']; ?></textarea>
        </div>
        <div class="form-row">
        <div class="form-group">
            <label for="mainImage">Tải ảnh sản phẩm:</label>
            <input type="file" id="mainImage" name="mainImage" accept="image/*">
            <img src="../images/<?php echo $row_up['HinhAnh']; ?>" alt="" width="100" style="margin-top: 10px;">
            <div id="mainPreview"></div>
        </div>
        <div class="form-group">
            <label for="descImages">Tải ảnh mô tả:</label>
            <input type="file" id="descImages" name="descImages[]" multiple accept="image/*">
            <div id="descImagesContainer">
                <?php 
                    $descImages = explode(";", $row_up['Hinh']); 
                    foreach ($descImages as $image) {
                        if (!empty($image)) {
                            echo '<img src="../images/' . $image . '" alt="" class="desc-image">';
                        }
                    }
                ?>
            </div>
        </div>
</div>
        <button type="submit" class="submit-btn" name="addsp">Cập nhật</button>
    </form>
</div>

</body>
</html>
