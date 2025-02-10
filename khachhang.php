<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="khachhang.css"> 
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">   
    <title>Document</title>
</head>  
<body>
    <div class="sidebar">
        <div class="logo">
            <h3>DrinkStore</h3>
            <hr></hr>
        </div>
        <nav>
            <ul>
                <div class="title">TỔNG QUAN</div>
                <li class="active"><a href="#">Khách hàng</a></li>
                <li class="active"><a href="#">Đơn hàng</a></li>
                <li class="active"><a href="#">Hóa đơn</a></li>
                <div class="title">QUẢN LÝ SẢN PHẨM</div>
                <li class="active"><a href="#">Sản phẩm</a></li>
                <li class="active"><a href="#">Thêm sản phẩm</a></li>
                <li class="active"><a href="#">Danh mục</a></li>
                <li class="active"><a href="#">Nhà cung cấp</a></li>
                <li class="active"><a href="#">Nhân viên</a></li>
                <div class="title">QUẢN LÝ NHÂN VIÊN</div>
                <li class="active"><a href="#">Thêm nhân viên</a></li>
                <div class="title">THỐNG KÊ</div>
                <li class="active"><a href="#">Nhân viên</a></li>
                <li class="active"><a href="#">Nhân viên</a></li>
                <li class="active"><a href="#">Nhân viên</a></li>
            </ul>
        </nav>
    </div>

    <div class="top-bar">
        <div class="breadcrumb">
            Pages / Thêm sản phẩm
        </div>

        <?php
            session_start();
        ?>

        <div class="user-info">
            <?php if (isset($_SESSION['lname'])): ?>
                <p>Xin chào, <span class="username"><?php echo htmlspecialchars($_SESSION['lname']); ?></span></p>
                <a href="dangxuat.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            <?php endif; ?>
        </div>
        
    </div>

    <div class="container">
        <h2>Thêm sản phẩm mới</h2>
        <form>
            <div class="form-group">
                <label for="category">Danh mục sản phẩm</label>
                <select id="category" name="category">
                    <option>-Chọn-</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" id="name" name="name" placeholder="Nhập tên sản phẩm">
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" placeholder="Nhập mô tả sản phẩm"></textarea>
            </div>
            <div class="form-group">
                <label for="quantity">Số lượng</label>
                <input type="number" id="quantity" name="quantity" placeholder="Nhập số lượng sản phẩm">
            </div>
            <div class="form-group">
                <label for="price">Giá bán (VND)</label>
                <input type="number" id="price" name="price" placeholder="Nhập giá bán sản phẩm">
            </div>
            <div class="form-group">
                <label for="image">Tải ảnh sản phẩm:</label>
                <input type="file" id="image" name="image">
            </div>
            <button type="submit" class="submit-btn">Thêm</button>
        </form>
    </div>
</body>
</html>
