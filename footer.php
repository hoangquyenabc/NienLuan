<button class="scroll-to-top" >
        <i class="fas fa-arrow-up"></i> 
</button>
<footer>
        <div class="container-ft">
            <div class="footer-section">
                <h4 style=" color: #7fad39; margin-right: 270px ; text-transform: uppercase;">About</h4>
                <ul>
                    <li><a href="#">Our Story</a></li>
                    <li><a href="#">Awards</a></li>
                    <li><a href="#">Our Team</a></li>
                    <li><a href="#">Career</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4 style="color: #7fad39;  margin-right: 270px ; text-transform: uppercase;">Company</h4>
                <ul>
                    <li><a href="#">Our Services</a></li>
                    <li><a href="#">Clients</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Press</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4 style=" color: #7fad39; margin-right: 270px ; text-transform: uppercase;">Resources</h4>
                <ul>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="newsletter">
            <h4 style=" color: #7fad39; margin-right: 170px ; text-transform: uppercase;">
                Đăng Ký
            </h4>

                <input type="email" placeholder="Nhập địa chỉ email">
                <button>Đăng Ký</button>
                <p>Nhận những cập nhật mới nhất qua email.</p>
            </div>
        </div>
        <div class="social-icons">
              <a href="#"><i class="fa-brands fa-twitter"></i></a>
              <a href="#"><i class="fa-brands fa-instagram"></i></a>
              <a href="#"><i class="fa-brands fa-facebook"></i></a>
              <a href="#"><i class="fa-brands fa-youtube"></i></a>
        </div>
        <hr />
        <div class="footer-bottom">
            <p>© 2024 All rights reserved. This template is made with ♥ by Colorlib.com</p>
            
        </div>
    </footer>
    <script>
        // Hàm thay đổi ảnh lớn
        function changeMainImage(newSrc) {
            const mainImage = document.getElementById('mainImage');
            mainImage.src = newSrc;
        }
        function showTab(tabName) {
    // Ẩn tất cả các phần content
    var contents = document.querySelectorAll('.tab-content');
    contents.forEach(function(content) {
        content.style.display = 'none';  // Ẩn tất cả nội dung
    });
    
    // Hiển thị phần content tương ứng
    document.getElementById(tabName).style.display = 'block';

    // Ẩn/hiện tab buttons
    var buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(function(button) {
        button.classList.remove('active-tab');  // Loại bỏ lớp active-tab của tất cả tab buttons
    });
    
    // Đổi màu cho tab đang được chọn
    var activeButton = document.querySelector('button[onclick="showTab(\'' + tabName + '\')"]');
    activeButton.classList.add('active-tab');  // Thêm lớp active-tab vào tab được chọn
}



        
    </script>
    
    <script>
        function deleteProduct(element) {
            const productId = element.getAttribute('data-product');
            if (confirm("Bạn có chắc muốn xóa sản phẩm này?")) {
                fetch('delete_product.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        element.closest('div').remove();
                        alert("Sản phẩm đã được xóa!");
                    } else {
                        alert("Không thể xóa sản phẩm!");
                    }
                })
                .catch(error => {
                    console.error("Lỗi:", error);
                    alert("Đã xảy ra lỗi!");
                });
            }
        }
    </script>
    <script>
    // Giả lập số lượng sản phẩm trong giỏ hàng
        const cartItems = 0; // Đặt số lượng sản phẩm ở đây

        const formButton = document.getElementById("form-button");
        const emptyCartMessage = document.getElementById("empty-cart-message");

        if (cartItems > 0) {
            formButton.disabled = false; // Bật nút "Tiếp tục"
            emptyCartMessage.style.display = "none"; // Ẩn thông báo giỏ hàng trống
        } else {
            formButton.disabled = true; // Vô hiệu hóa nút "Tiếp tục"
        }
    </script>
    <script>
        // Kiểm tra nếu giỏ hàng không trống và người dùng nhấn nút "Tiếp tục"
        document.querySelector('input[name="btndathang"]').addEventListener('click', function(e) {
            var isCartEmpty = <?php echo $isCartEmpty ? 'true' : 'false'; ?>;
            
            // Nếu giỏ hàng không trống, chuyển hướng sang trang "order.php"
            if (!isCartEmpty) {
                document.getElementById('orderForm').action = 'order.php';  // Chuyển hướng trang
                document.getElementById('orderForm').submit();  // Gửi form
            } else {
                e.preventDefault();  // Ngừng việc gửi form nếu giỏ hàng trống
            }
        });
    </script>

    <script src="js/trangchu.js"></script>
    <script src="js/cart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>