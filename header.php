<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nội thất HUJU</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/order.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/category.css">
    <link rel="stylesheet" href="css/detail.css"> 
    <link rel="stylesheet" href="css/cart.css"> 
    <link rel="stylesheet" href="css/checkout.css"> 
    <link rel="stylesheet" href="css/review.css"> 
    <link rel="stylesheet" href="css/update.css"> 
    <link rel="stylesheet" href="css/chat.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
       
    </style>
    
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="home.php"> 
                <img src="images/lo.png" alt="Sky Store Logo">
            </a>
        </div>
        <nav class="nav">
            <a href="home.php">Trang Chủ</a>
            <a href="products.php">Sản Phẩm</a>
            <a href="contact.php">Liên Hệ</a>
            <a href="about.php">Giới Thiệu</a>
        </nav>
        <div class="header-icons">
        <form method="GET" action="search.php">  
        <div class="search-container">
    
        <input type="text" name="search_query" placeholder="Tìm kiếm...">
        <button class="search-icon" type="submit">
            <i class="fas fa-search"></i>
        </button>
    
        </div>
        </form>

           
        <?php if (!isset($_SESSION['username'])): ?>
                <a href="dangnhap.php" class="icon">
                    <i class="fas fa-user"></i>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['username'])): ?>
                <div class="dropdown">
                    
                    <a 
                        href="#" 
                        class="custom-dropdown-toggle" 
                        id="userDropdown" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                        <?php echo "Xin chào, " . htmlspecialchars($_SESSION['username']); ?>
                    </a>

                   
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="customer.php">Thông tin</a></li>
                        <li><a class="dropdown-item" href="orderhistory.php">Đơn hàng</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="dangxuat.php">Đăng xuất</a></li>
                    </ul>
                </div>
            <?php endif; ?>
            
        <a href="cart.php" class="icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count">
            <?php
                
                $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                $count = 0;
                foreach ($cart as $item) {
                    $count += $item['quantity'];
                }
                echo $count;
                ?>

            </span>
        </a>
        </div>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>  
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    