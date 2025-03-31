<?php
session_start();
require_once "connect.php";
if (isset($_GET['id']) && isset($_POST['quantity'])) {
    $idsp = intval($_GET['id']);
    $qty = intval($_POST['quantity']);

    $cart = $_SESSION['cart'] ?? [];

        foreach ($cart as &$item) {
            if ($item['ID_SP'] == $idsp) {
                $item['quantity'] = $qty;
                break;
            }
        }
       
        $_SESSION['cart'] = $cart;
    
    }

header("Location: cart.php");
exit();
?>
