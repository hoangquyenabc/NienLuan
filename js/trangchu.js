
document.querySelector('.scroll-to-top').addEventListener('click', function() { //Phương thức .addEventListener() cho phép gắn nhiều sự kiện khác nhau vào cùng một phần tử mà không ghi đè lẫn nhau.
    window.scrollTo({ //window.scrollTo là một phương thức trong JavaScript, được sử dụng để cuộn trang đến một vị trí cụ thể trên trang web. Bạn có thể cuộn trang đến tọa độ cụ thể trên trục ngang (X) và trục dọc (Y).
        top: 0,
        behavior: 'smooth'
    });
});
// document.addEventListener("DOMContentLoaded", function() {
//     var productRow = document.querySelector(".product-row");
//     var productItems = productRow.querySelectorAll(".product-item");

//     if (productItems.length === 1) {
//         productRow.style.justifyContent = "flex-start";  // Căn trái khi có 1 sản phẩm
//     } else if (productItems.length === 2) {
//         productRow.style.justifyContent = "flex-start";  // Căn đều với 2 sản phẩm
//     } else if (productItems.length === 3) {
//         productRow.style.justifyContent = "flex-start";  // Căn từ trái khi có 3 sản phẩm
//         productItems.forEach(item => {
//             item.style.flexBasis = "calc(33.33% - 20px)";  // Điều chỉnh chiều rộng của mỗi sản phẩm
//         });
//     } else if (productItems.length === 4) {
//         productRow.style.justifyContent = "space-evenly";  // Phân phối đều với 4 sản phẩm
//     } else {
//         productRow.style.justifyContent = "space-evenly";  // Mặc định cho số lượng sản phẩm khác
//     }
// });

