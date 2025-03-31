<?php 
    require('includes/header.php'); 
    require_once "connect.php";

?>

<div class="container-category">

        <div class="product-list-dm">
        <?php
            if(isset($_POST["tim"])){
                $tim = $_POST["timkiem"];
                if($tim == ""){
                    echo "vui long nhap tu khoa tim kiem";
                } else{
                    $sql = "SELECT * FROM loai_sp WHERE Ten_L LIKE '%$tim%' ORDER BY Ten_L";
                    $query = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($query);
                    if($count == 0){
                        // echo "Khong ket qua: ". $tim;
                    } else{
                        
                    }
                }
            }
        ?>

            <form action="search.php" method="POST" onsubmit="return validateForm()">
                <div class="search-bar">
                    <input type="text" id="searchInput" id="timkiem" name ="timkiem" placeholder="Tìm kiếm...">
                    <button type="submit" class="tim" name="tim" >Tìm</button>
                </div>
            </form>
            <div class="header">Danh sách sản phẩm</div>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên danh muc</th>
                        <th>Hình ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">

                    <?php
                        while ($row = mysqli_fetch_assoc($query)){
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
                        </td>
                        </a>
                    </tr> 
                    <?php 
                    }  
                    ?>      

                </tbody>
            </table>
            </div>

                  

        <div class="container-dm">

        <?php
            if(isset($_POST['add'])){
                $name = $_POST['name'];
                $image = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $sql = "INSERT INTO loai_sp (Ten_L, HinhAnh_L) VALUES ('$name', '$image')";
                $query = mysqli_query($conn,$sql);
                move_uploaded_file($image_tmp, 'images/anhdanhmuc/' .$image);
                

            }
        ?>
        
            <h2>Thêm danh mục mới</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="category">Danh mục sản phẩm</label>
                    <select id="category" name="category">
                        <option>-Chọn-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Tên danh mục</label>
                    <input type="text" id="name" name="name" placeholder="Nhập tên danh mục" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Tải ảnh danh mục:</label>
                    <input type="file" id="image" name="image">
                </div>
                <button type="submit" class="submit-btn" name="add" >Thêm</button>
            </form>
        </div>
    
 

    </div>

    <script>
        document.querySelector('.submit-btn').addEventListener('click', function() {
            const productName = document.querySelector('#name').value;
            const confirmDelete = alert(`Bạn đã thêm sản phẩm "${productName}" thành công!`);
        });

        
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
        const input = document.getElementById("timkiem").value.trim(); //Phương thức trim() loại bỏ khoảng trắng ở đầu và cuối chuỗi.
        if (input === "") {
            alert("Vui lòng nhập từ khóa trước khi tìm kiếm!");
            return false; 
        }
        return true; 
        }
    </script>

</body>
</html>    


    