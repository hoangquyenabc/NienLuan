<button class="scroll-to-top">
        <i class="fas fa-chevron-up"></i> 
</button>
<!-- <a href="index.php" class="scroll-to-top-chatbot">
    <i class="fas fa-comments"></i>
</a>             -->
<df-messenger
  chat-title="HuJuShop"
  agent-id="b4479de2-4a2a-4e5f-b34d-d78bcd66412a"
  language-code="en"
></df-messenger>


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
            <p>Copyright © 2025 Nội Thất HUJU.</p>
            
        </div>
    </footer>
    <script>
        function changeMainImage(newSrc) { 
            const mainImage = document.getElementById('mainImage');
            mainImage.src = newSrc;
        }
        function showTab(tabName) {
            var contents = document.querySelectorAll('.tab-content'); 
            contents.forEach(function(content) {
                content.style.display = 'none';  
            });

            document.getElementById(tabName).style.display = 'block'; 
            var buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(function(button) { 
                button.classList.remove('active-tab'); 
            });
            var activeButton = document.querySelector('button[onclick="showTab(\'' + tabName + '\')"]');
            activeButton.classList.add('active-tab');  
        }  
    </script>
    <script>
        const cartItems = 0; 
        const formButton = document.getElementById("form-button"); 
        const emptyCartMessage = document.getElementById("empty-cart-message"); 

        if (cartItems > 0) {
            formButton.disabled = false; 
            emptyCartMessage.style.display = "none"; 
        } else {
            formButton.disabled = true; 
        }
    </script>
    <script>
        document.querySelector('input[name="btndathang"]').addEventListener('click', function(e) { 
            var isCartEmpty = <?php echo $isCartEmpty ? 'true' : 'false'; ?>; 
            
            if (!isCartEmpty) {
                document.getElementById('orderForm').action = 'order.php'; 
                document.getElementById('orderForm').submit();  
            } else {
                e.preventDefault(); 
            }
        });     
    </script>
    
    
    <script src="js/cart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
</body>
</html>