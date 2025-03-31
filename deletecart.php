<?php
    session_start();
    $idsp = $_GET['id']; 
    $cart = [];
    if(isset($_SESSION['cart'])) { 
        $cart = $_SESSION['cart'];
    }
    for ($i = 0; $i < count($cart); $i++) { 
        if($cart[$i]['ID_SP'] == $idsp) { 
            array_splice($cart, $i, 1); 
            break;
        }
    }
    $_SESSION['cart'] = $cart;
    header("Location: cart.php");

?>