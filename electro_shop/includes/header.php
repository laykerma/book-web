<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <h1>ElectroShop</h1>
    <nav>
        <a href="index.php">Trang chủ</a>
        <a href="category.php?id=1">Điện thoại</a>
        <a href="category.php?id=2">Laptop</a>
        <a href="category.php?id=4">Phụ kiện</a>
        <a href="cart.php">Giỏ hàng</a>

        <?php if (!isset($_SESSION['user'])): ?>
            <a href="login.php">Đăng nhập</a>
        <?php else: ?>
            <a href="logout.php">Đăng xuất</a>
        <?php endif; ?>
    </nav>
</header>
