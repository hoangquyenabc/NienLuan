<?php 
    require('header.php'); 
    require_once "connect.php";

?>
<div class="message-container">
    <div class="message-title">Trò chuyện ngay</div>
    <div class="message-box-chat" id="chat"></div>
    <input type="text" class="message-input" id="input" placeholder="Nhập tin nhắn của bạn..." />
    <button class="message-button" id="button">
        <i class="fa-brands fa-telegram" style="font-size: 3rem;"></i>
    </button>
</div>

<?php 
    require('footer.php'); 

?>  