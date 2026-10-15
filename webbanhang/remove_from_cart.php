<?php
session_start();
$id = $_GET['id'] ?? 0;

if (isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

header("Location: cart.php");
exit;
