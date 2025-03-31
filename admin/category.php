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
    <div class="product-list-dm">
        <form action="search.php" method="POST">
            <div class="search-bar">
                <input type="text" id="searchInput" name="timkiem" placeholder="Tìm kiếm ...">
                <button type="submit" class="tim" name="tim">Tìm</button>
            </div>
        </form>

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
                    <?php if ($row['TrangThai_L'] == 'active') : ?>
                        <button class="action-btn delete-btn" style="font-size: 16px;"
                            onclick="Delete(<?php echo $row['ID_L']; ?>, '<?php echo $row['Ten_L']; ?>')">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    <?php else : ?>
                        <button class="action-btn delete-btn" style="font-size: 16px;" disabled>
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    <?php endif; ?>
                    </td>
                </tr> 
                <?php 
                }  
                ?>       
            </tbody>
        </table>
    </div>

    <div class="container-dm">
        <h2>Thêm danh mục mới</h2>
        
        <?php
            if (isset($_POST['add'])) {
                $name = $_POST['name'];
                $image = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                if (move_uploaded_file($image_tmp, 'images/anhdanhmuc/' . $image)) {
                    $sql = "INSERT INTO loai_sp (Ten_L, HinhAnh_L) VALUES ('$name', '$image')";
                    $query = mysqli_query($conn, $sql);
                    if ($query) {
                        echo "<script>
                                alert('Danh mục đã được thêm thành công!');
                                window.location.href = 'category.php'; // Điều hướng lại trang danh mục
                              </script>";
                    } else {
                        echo "<script>alert('Có lỗi xảy ra khi thêm danh mục.');</script>";
                    }
                } else {
                    echo "<script>alert('Lỗi khi tải ảnh lên!');</script>";
                }
            }
        ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Tên danh mục</label>
                <input type="text" id="name" name="name" placeholder="Nhập tên danh mục" required>
            </div>
            
            <div class="form-group">
                <label for="image">Tải ảnh danh mục:</label>
                <input type="file" id="image" name="image" required>
            </div>
            <button type="submit" class="submit-btn" name="add">Thêm</button>
        </form>
    </div>
</div>

<script>
    function Delete(id, name) {
        if (confirm(`Bạn có chắc chắn muốn xóa loại sản phẩm "${name}" không?`)) {
            window.location.href = `deletecategory.php?id=${id}`;
        } else {
            alert("Hủy xóa sản phẩm.");
        }
    }
</script>

</body>
</html> 
