<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $action = $_POST['action'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 1);

    if (isset($_SESSION['cart'][$id])) {
        if ($action === "increase") {
            $_SESSION['cart'][$id]['quantity']++;
        } elseif ($action === "decrease") {
            if ($_SESSION['cart'][$id]['quantity'] > 1) {
                $_SESSION['cart'][$id]['quantity']--;
            }
        } else {
            // nhập trực tiếp -> tối thiểu là 1
            $_SESSION['cart'][$id]['quantity'] = max(1, $quantity);
        }
    }
}

header("Location: cart.php");
exit;
