// Hàm tìm kiếm sản phẩm
function searchProduct() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const tableRows = document.querySelectorAll('#productTableBody tr'); // #productTableBody tr là một CSS selector dùng để chỉ định rằng bạn muốn chọn tất cả các phần tử <tr> bên trong phần tử có id="productTableBody"

    tableRows.forEach(row => {

        // .forEach(row => { ... }): Duyệt qua tất cả các phần tử trong tableRows (tức là từng hàng của bảng).
        // Mỗi row đại diện cho một dòng trong bảng, chứa thông tin của một sản phẩm.

        const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase(); //row.querySelector('td:nth-child(2)'): Tìm phần tử <td> thứ 2 trong mỗi hàng (vì giả sử cột thứ 2 là cột chứa tên sản phẩm).
        if (productName.includes(searchInput)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Thêm sự kiện khi nhấn nút Sửa và Xóa
document.querySelectorAll('.edit-btn').forEach(button => { //Nó được sử dụng để chọn tất cả các phần tử trong trang HTML có class là .edit-btn.
    button.addEventListener('click', function () {
        const row = this.closest('tr');

        //this trong trường hợp này là đối tượng button, tức là nút mà người dùng nhấn vào.
        //closest('tr') là một phương thức giúp tìm phần tử <tr> gần nhất (cha của nút) trong DOM. Điều này giúp xác định dòng (<tr>) mà nút sửa đang nằm trong đó.

        const productName = row.querySelector('td:nth-child(2)').textContent;
        alert(`Bạn muốn sửa sản phẩm: ${productName}`);
    });
});

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function () {
        const row = this.closest('tr');
        const productName = row.querySelector('td:nth-child(2)').textContent;
        const confirmDelete = confirm(`Bạn có chắc muốn xóa sản phẩm: ${productName}?`);
        if (confirmDelete) {
            row.remove();
            alert(`Sản phẩm "${productName}" đã được xóa.`);
        }
    });
});
