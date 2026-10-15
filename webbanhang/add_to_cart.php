<?php
include 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_GET['id'] ?? 0;
$id = intval($id);

// Lấy thông tin sách
$sql = "SELECT * FROM books WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $book = $result->fetch_assoc();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Nếu đã có trong giỏ thì +1, chưa có thì thêm
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $book['id'],
            'title' => $book['title'],
            'price' => $book['price'],
            'quantity' => 1
        ];
    }
}
header("Location: cart.php");
exit;
