var updateBtns = document.getElementsByClassName('update-cart')

for (i=0; i < updateBtns.length; i++){
    updateBtns[i].addEventListener('click', function(){
        var productId = this.dataset.product
        var action = this.dataset.action
        console.log('productId', productId, 'action', action)
        console.log('user: ', user)
        if (user === "AnonymousUser"){
            console.log('User not log in')
        } else{
            updateUserOrder(productId, action)
        }
    })
}

function updateUserOrder(productId, action){
    console.log('user logged in, success add')
    var url = '/update_item/'
    fetch(url,{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRFToken': csrftoken
        },
        body: JSON.stringify({'productId': productId, 'action': action})

    })
    .then((response) => {
        return response.json()
    })
    .then((data) => {
        console.log('data', data)
        location.reload()
    })

}

document.querySelector('.scroll-to-top').addEventListener('click', function() { //Phương thức .addEventListener() cho phép gắn nhiều sự kiện khác nhau vào cùng một phần tử mà không ghi đè lẫn nhau.
    window.scrollTo({ //window.scrollTo là một phương thức trong JavaScript, được sử dụng để cuộn trang đến một vị trí cụ thể trên trang web. Bạn có thể cuộn trang đến tọa độ cụ thể trên trục ngang (X) và trục dọc (Y).
        top: 0,
        behavior: 'smooth'
    });
});

document.querySelectorAll('.chg-quantity').forEach(function(button) {
    button.addEventListener('click', function() {
        var action = this.getAttribute('data-action'); // Lấy hành động (add/remove)
        var quantityElement = document.getElementById('quantity');
        var currentQuantity = parseInt(quantityElement.textContent); // Lấy số lượng hiện tại

        if (action === 'add') {
            // Tăng số lượng
            currentQuantity++;
        } else if (action === 'remove' && currentQuantity > 1) {
            // Giảm số lượng, đảm bảo số lượng không nhỏ hơn 1
            currentQuantity--;
        }

        // Cập nhật lại số lượng
        quantityElement.textContent = currentQuantity;
    });
});
