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
    <link rel="stylesheet" href="css/update.css"> 
    <style>

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
            $current_page = basename($_SERVER['PHP_SELF']); 
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
                    $active_class = $class; 
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
                <li class="<?php echo $current_page == 'addproduct.php' ? $active_class : ''; ?>"><a href="addproduct.php">Thêm sản phẩm mới</a></li>
                <li class="<?php echo $current_page == 'category.php' ? $active_class : ''; ?>"><a href="category.php">Danh mục</a></li>
                
                
                <div class="title">THỐNG KÊ</div>
                <li class="<?php echo $current_page == 'tkorder.php' ? $active_class : ''; ?>"><a href="tkorder.php">Tổng quan</a></li>
                <li class="<?php echo $current_page == 'tkproduct.php' ? $active_class : ''; ?>"><a href="tkproduct.php">Sản phẩm</a></li>
                <li class="<?php echo $current_page == 'tktotal.php' ? $active_class : ''; ?>"><a href="tktotal.php">Doanh thu</a></li>
                <div class="title">QUẢN LÝ NHÂN VIÊN</div>
                <li class="<?php echo $current_page == 'staff.php' ? $active_class : ''; ?>"><a href="staff.php">Nhân viên</a></li>
                <li class="<?php echo $current_page == 'addstaff.php' ? $active_class : ''; ?>"><a href="addstaff.php">Thêm nhân viên mới</a></li>
                <li class="<?php echo $current_page == 'c.php' ? $active_class : ''; ?>"><a href="c.php">Liên hệ</a></li>
               
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

            return true; 
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

