<?php
    session_start();
    $idsp = $_GET['id'];
    $qty = $_POST['quantity'];
    $cart = [];
    if(isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }
    for ($i = 0; $i < count($cart); $i++) {
        if($cart[$i]['ID_SP'] == $idsp) {
            $cart[$i]['quantity'] = $qty;
            break;
        }
    }
    $_SESSION['cart'] = $cart;
    header("Location: cart.php");

?>