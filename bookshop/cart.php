<?php
session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (!in_array($id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $id;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Giỏ hàng</title></head>
<body>
<h1>🛒 Giỏ hàng</h1>
<?php
if (empty($_SESSION['cart'])) {
    echo "Giỏ hàng trống. <a href='index.php'>Mua tiếp</a>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $id) {
        echo "<li>Sách ID: $id</li>";
    }
    echo "</ul>";
    echo "<a href='checkout.php'>Thanh toán</a>";
}
?>
</body>
</html>
