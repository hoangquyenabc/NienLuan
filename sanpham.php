<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="khachhang.css"> 
    
    <script src="sanpham.js"></script> 
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
        <div class="user-info">
            <p>Xin chào, <span class="username">Admin</span></p>
            <a href="#" class="logout-btn" >
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    <div class="container">
        <!-- Khung tìm kiếm sản phẩm -->
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm...">
            <button onclick="searchProduct()" class="tim">Tìm</button>
        </div>

        <!-- Khung danh sách sản phẩm -->
        <div class="product-list">
            <div class="header">Danh sách sản phẩm</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá bán</th>
                        <th>Số lượng</th>
                        <th>Danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <tr>
                        <td>1</td>
                        <td>Sản phẩm A</td>
                        <td>200,000 VND</td>
                        <td>10</td>
                        <td>Nước ngọt</td>
                        <td>
                            <button class="action-btn edit-btn"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="action-btn delete-btn"><i class="fas fa-trash"></i> Xóa</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Sản phẩm B</td>
                        <td>150,000 VND</td>
                        <td>20</td>
                        <td>Trà sữa</td>
                        <td>
                            <button class="action-btn edit-btn"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="action-btn delete-btn"><i class="fas fa-trash"></i> Xóa</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
