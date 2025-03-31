<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nội thất HUJU</title>
    <link rel="icon" type="image/png" href="../images/logo.png">
    <link rel="stylesheet" href="css/category.css"> 
    <link rel="stylesheet" href="css/addproduct.css"> 
    <link rel="stylesheet" href="css/products.css"> 
    <link rel="stylesheet" href="css/vieworder.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bill.css"> 
    <link rel="stylesheet" href="css/thproduct.css"> 
    
    
    

    
   

    <style>
        .custom-dropdown-toggle {
            color: #000000; /* Màu chữ đen */
            text-decoration: none; /* Loại bỏ gạch chân */
            transition: all 0.3s ease; /* Hiệu ứng mượt */
            font-size: 20px;
        }

    /* Hiệu ứng hover */
        .custom-dropdown-toggle:hover {
            color: #e91e63; 
            text-decoration: none; 
        }

    /* Hiệu ứng khi nhấn */
        .custom-dropdown-toggle:active {
            color: #e91e63; 
            text-decoration: none; 
        }

        .dropdown{
            padding-right: 20px;
        }
        .container {
                margin: 100px auto; /* Canh giữa theo chiều ngang */
                padding: 20px;
                background-color: white;
                border-radius: 7px;
                box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
                width: 100%; /* Sử dụng phần trăm để chiều rộng tự động thay đổi */
                max-width: 3200px; /* Đặt giới hạn chiều rộng tối đa */
            }


            .container h2 {
                color: #e91e63;
                margin-bottom: 20px;
                text-align: center;
            }

            .form-row {
                display: flex;
                justify-content: space-between;
                gap: 40px; /* Tạo khoảng cách giữa các cột */
            }

            .form-group {
                flex: 1; /* Các nhóm chia đều không gian */
                margin-bottom: 20px;
                display: flex;
                flex-direction: column;
            }

            .form-group label {
                display: block;
                margin-bottom: 5px;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            textarea {
                resize: none;
            }

            .submit-btn {
                display: block;
                width: 22%;
                padding: 12px;
                background-color: #e91e63;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 15px;
                cursor: pointer;
                /* margin: 0 auto; Canh giữa nút */
            }

            .submit-btn:hover {
                background-color: #d01750;
            }
            .breadcrumb {
                padding: 8px 15px;
                margin-bottom: 6px;
                list-style: none;
                /* background-color: #f5f5f5; */
                border-radius: 4px;
            }
            .container-editproduct {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                max-width: 900px; /* Điều chỉnh độ rộng tối đa */
                width: 80%; /* Chiếm toàn bộ chiều rộng */
                margin: 0 auto; /* Căn giữa container */
                margin-top: 100px;
                margin-left: 365px;
                margin-left: 525px;
            }
            
        
        
        
    </style>
</head>  
<body>
    <div class="sidebar">
        <div class="logo">
            <h3>HujuShop</h3>
            <hr></hr>
        </div>
        <nav>
        <?php
            $current_page = basename($_SERVER['PHP_SELF']);  // Lấy tên tệp hiện tại

            // Mảng các trang và tên liên kết tương ứng
            $pages = [
                'customer.php' => 'active',
                'order.php' => 'active',
                'checkout.php' => 'active',
                'rating.php' => 'active',
                'category.php' => 'active',
                'products.php' => 'active',
                'tkorder.php' => 'active',
                'tkproduct.php' => 'active',
                'tktotal.php' => 'active',
                'staff.php' => 'active',
                'addproduct.php' => 'active'
            ];

            $active_class = '';

            foreach ($pages as $page => $class) {
                if ($current_page == $page) {
                    $active_class = $class;  // Gán lớp active cho trang hiện tại
                }
            }
        ?>
            <ul>
                <div class="title">TỔNG QUAN</div>
                <li class="<?php echo $current_page == 'customer.php' ? $active_class : ''; ?>"><a href="customer.php">Khách hàng</a></li>
                <li class="<?php echo $current_page == 'listorder.php' ? $active_class : ''; ?>"><a href="listorder.php">Đơn hàng</a></li>
                <li class="<?php echo $current_page == 'rating.php' ? $active_class : ''; ?>"><a href="rating.php">Đánh giá</a></li>    
                <div class="title">QUẢN LÝ SẢN PHẨM</div>
                <li class="<?php echo $current_page == 'products.php' ? $active_class : ''; ?>"><a href="products.php">Sản phẩm</a></li>
                <li class="<?php echo $current_page == 'addproduct.php' ? $active_class : ''; ?>"><a href="">Thêm sản phẩm mới</a></li>
                <li class="<?php echo $current_page == 'category.php' ? $active_class : ''; ?>"><a href="category.php">Danh mục</a></li>
                
                <div class="title">THỐNG KÊ</div>
                <li class="<?php echo $current_page == 'tkorder.php' ? $active_class : ''; ?>"><a href="tkorder.php">Tổng quan</a></li>
                <li class="<?php echo $current_page == 'tkproduct.php' ? $active_class : ''; ?>"><a href="tkproduct.php">Sản phẩm</a></li>
                <li class="<?php echo $current_page == 'tktotal.php' ? $active_class : ''; ?>"><a href="tktotal.php">Doanh thu</a></li>
                <div class="title">QUẢN LÝ NHÂN VIÊN</div>
                <li class="<?php echo $current_page == 'staff.php' ? $active_class : ''; ?>"><a href="staff.php">Nhân viên</a></li>
                <li class="<?php echo $current_page == 'addstaff.php' ? $active_class : ''; ?>"><a href="">Thêm nhân viên mới</a></li>
                <!-- <li class="<?php echo $current_page == 'c.php' ? $active_class : ''; ?>"><a href="c.php">Liên hệ</a></li> -->
 
               
            </ul>
        </nav>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function tinhSoLuongMoi(form) {
            let id = form.querySelector("input[name='id']").value; 
            let soLuongHienTai = parseInt(document.getElementById('SoLuongHienTai_' + id).value);
            let soLuongThem = parseInt(document.getElementById('SoLuongThem_' + id).value);

            if (isNaN(soLuongThem) || soLuongThem < 0) {
                alert("Số lượng thêm phải là số nguyên không âm!");
                return false;
            }

            let tongSoLuong = soLuongHienTai + soLuongThem;
            document.getElementById('SoLuongMoi_' + id).value = tongSoLuong;

            return true; // Cho phép gửi form
        }
       
        function toggleReasonInput() {
            var status = document.getElementById("order_status").value;
            var reasonContainer = document.getElementById("cancel_reason_container");
            if (status === "Đã hủy") {
                reasonContainer.style.display = "block";
            } else {
                reasonContainer.style.display = "none";
            }
        }


    </script>

