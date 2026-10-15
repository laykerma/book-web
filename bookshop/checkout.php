<?php
session_start();
if (!empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    echo "<h1>🎉 Thanh toán thành công!</h1><a href='index.php'>Quay lại trang chủ</a>";
} else {
    echo "Không có sản phẩm trong giỏ!";
}
?>
