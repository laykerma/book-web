<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$store_cart_count = 0;
foreach ($_SESSION['cart'] ?? [] as $store_cart_item) {
    $store_cart_count += (int) ($store_cart_item['quantity'] ?? 0);
}
$store_is_home = basename($_SERVER['PHP_SELF']) === 'index.php';
?>
<header class="store-header">
    <div class="store-header-inner">
        <a class="store-brand" href="index.php" aria-label="BookStore - Trang chủ">
            <span class="brand-symbol" aria-hidden="true">B</span>
            <span>Book<span class="brand-accent">Store</span></span>
        </a>
        <nav class="store-nav" aria-label="Điều hướng chính">
            <a class="<?= $store_is_home ? 'is-current' : '' ?>" href="index.php">Trang chủ</a>
            <a href="index.php#categories">Danh mục</a>
            <a href="index.php#featured-books">Sách mới</a>
        </nav>
        <form class="store-search" method="get" action="index.php" role="search">
            <?php if (!empty($_GET['cat'])): ?><input type="hidden" name="cat" value="<?= htmlspecialchars($_GET['cat'], ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
            <label class="visually-hidden" for="book-search">Tìm sách</label>
            <input id="book-search" type="search" name="q" placeholder="Tìm tên sách, tác giả..." value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" aria-label="Tìm kiếm">⌕</button>
        </form>
        <div class="store-actions">
            <a class="cart-link" href="cart.php"><span aria-hidden="true">▣</span><span>Giỏ hàng</span><?php if ($store_cart_count > 0): ?><span class="cart-count"><?= $store_cart_count ?></span><?php endif; ?></a>
            <?php if (isset($_SESSION['user'])): ?>
                <span class="customer-greeting">Xin chào, <?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'bạn', ENT_QUOTES, 'UTF-8') ?></span>
                <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?><a class="account-link" href="admin.php">Quản trị</a><?php endif; ?>
                <a class="account-link" href="logout.php">Đăng xuất</a>
            <?php else: ?>
                <a class="account-link" href="login.php">Đăng nhập</a>
                <a class="signup-link" href="register.php">Tạo tài khoản</a>
            <?php endif; ?>
        </div>
    </div>
</header>