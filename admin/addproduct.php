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
    $category_query = "SELECT * FROM loai_sp";
    $category_result = mysqli_query($conn, $category_query);
    $brand_query = "SELECT ID_TH, Ten_TH FROM thuong_hieu";
    $brand_result = mysqli_query($conn, $brand_query);

    if (isset($_POST['addsp'])) {
        $category = $_POST['category'];
        $brand = $_POST['brand'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $length = $_POST['length'];
        $material = $_POST['material'];
        $description = $_POST['description'];
    
        $image_thumbnail = ''; 
        $desc_image_names = [];

        if (!empty($_FILES['mainImage']['name'])) { 
            $image_name = $_FILES['mainImage']['name']; 
            $image_tmp = $_FILES['mainImage']['tmp_name']; 

            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION); 
        
            if (in_array(strtolower($image_ext), $allowed_types)) { // Chuyen chuoi thanh chu thuong
                if (move_uploaded_file($image_tmp, "../images/" . $image_name)) { 
                    $image_thumbnail = $image_name; 
                }
            }
        }

        if (!empty($_FILES['descImages']['name'][0])) { 
            foreach ($_FILES['descImages']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['descImages']['error'][$key] === UPLOAD_ERR_OK) {
                    $desc_image_name = $_FILES['descImages']['name'][$key]; 
                    $desc_image_ext = pathinfo($desc_image_name, PATHINFO_EXTENSION);
                    if (in_array(strtolower($desc_image_ext), $allowed_types)) {
                        if (move_uploaded_file($tmp_name, "../images/" . $desc_image_name)) {
                            $desc_image_names[] = $desc_image_name; 
                        }
                    }
                }
            }
        }
        $image_list = !empty($desc_image_names) ? implode(";", $desc_image_names) : ''; 

        $sql = "INSERT INTO san_pham (ID_L, ID_TH, Ten_SP, MoTa, HinhAnh, KichThuoc, ChatLieu, Hinh, SoLuongKho) 
        VALUES ('$category', '$brand', '$name', '$description', '$image_thumbnail', '$length', '$material', '$image_list', 0)";
        $query = mysqli_query($conn, $sql);
        $last_id = mysqli_insert_id($conn);

        $current_time = date('Y-m-d H:i:s'); 
        $sql_time = "INSERT INTO thoi_diem (NgayGio) VALUES ('$current_time')";
        $time_query = mysqli_query($conn, $sql_time);

        $sql_price = "INSERT INTO co_gia (NgayGio, ID_SP, Gia) 
                      VALUES ('$current_time', '$last_id', '$price')";
        $price_query = mysqli_query($conn, $sql_price);

        if ($query && $time_query && $price_query) {
            echo "<script>
                    alert('Sản phẩm đã được thêm thành công!');
                    window.location.href = 'products.php';
                  </script>";
        } else {
            echo "<p>Lỗi khi thêm sản phẩm: " . mysqli_error($conn) . "</p>";
        }
    }
    

?>


    <h2 style="text-align: center; margin: 20px 0; font-size: 34px; color:#e91e63; margin-bottom: 20px;">Thêm sản phẩm mới</h2>
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
                            echo "<option value='" . $row['ID_TH'] . "'>" . $row['Ten_TH'] . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
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
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" placeholder="Nhập mô tả sản phẩm" required></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="mainImage">Tải ảnh sản phẩm:</label>
                <input type="file" id="mainImage" name="mainImage" required accept="image/*">
                <div id="mainPreview"></div>
            </div>
            <div class="form-group">
                <label for="descImages">Tải ảnh mô tả:</label>
                <input type="file" id="descImages" name="descImages[]" multiple required accept="image/*">
                <div id="descPreview"></div>
            </div>
        </div>
        <button type="submit" class="submit-btn" name="addsp">Thêm</button>
    </form>
</div>
<script>
    function previewImages(inputId, previewId, multiple) {
        let input = document.getElementById(inputId); 
        let preview = document.getElementById(previewId); 
        preview.innerHTML = '';  
        let files = Array.from(input.files); 

        if (multiple) {
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) { 
                    let reader = new FileReader(); 
                    reader.onload = function(e) {
                        let img = document.createElement('img'); 
                        img.src = e.target.result; 
                        img.style.width = "90px";
                        img.style.margin = "5px";
                        img.setAttribute("data-index", index); 
                        preview.appendChild(img); 
                    };
                    reader.readAsDataURL(file); 
                }
            });
        } else {
            if (files.length > 0 && files[0].type.startsWith('image/')) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = "90px";
                    img.style.margin = "5px";
                    preview.innerHTML = ''; 
                    preview.appendChild(img);
                };
                reader.readAsDataURL(files[0]);
            }
        }
    }
    document.getElementById('mainImage').addEventListener('change', function() {
        previewImages('mainImage', 'mainPreview', false); 
    });

    document.getElementById('descImages').addEventListener('change', function() {
        previewImages('descImages', 'descPreview', true);
    });

    </script>

</body>
</html>
