const form = document.querySelector('.login form');
const submitBtn = document.querySelector('.button input');
const errorText = document.querySelector('.error-text');

form.onsubmit = (e)=>{
	e.preventDefault();
}

submitBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", 'api/login.php', true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let response = JSON.parse(xhr.response.trim()); // Parse JSON response từ PHP
            console.log("Response: ", response);

            if (response.status === "success") {
                // Kiểm tra vai trò người dùng
                if (response.role === "0") {
                    // Vai trò là khách hàng (0), chuyển hướng đến trang chat mặc định
                    location.href = "chat.php?user_id= 1099588341"; // Thay "default_user_id" bằng ID người dùng mặc định
                } else if (response.role === "1") {
                    // Vai trò là nhân viên (1), chuyển hướng đến trang chọn khách hàng
                    location.href = "users.php"; // Trang quản lý khách hàng
                }
            } else {
                errorText.style.display = "block";
                errorText.textContent = response.message || "Đã xảy ra lỗi. Vui lòng thử lại!";
            }
        }
    };
    let formData = new FormData(form);
    xhr.send(formData);
};