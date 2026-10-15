<?php
include 'config.php';
include 'includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $id = $_GET['add'];

    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }
}

echo "<h2>Giỏ hàng</h2>";

$total = 0;

foreach ($_SESSION['cart'] as $id => $qty) {
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
    $p = mysqli_fetch_assoc($res);

    $sum = $qty * $p['price'];
    $total += $sum;

    echo "
        <div class='cart-item'>
            ".$p['name']." - ".$qty." x ".number_format($p['price'])." = ".number_format($sum)." đ
        </div>
    ";
}

echo "<h3>Tổng: ".number_format($total)." đ</h3>";
echo "<a href='checkout.php' class='btn'>Thanh toán</a>";

include 'includes/footer.php';
?>
