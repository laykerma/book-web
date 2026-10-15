
<?php
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cat = trim($_GET['cat'] ?? '');
    $q = trim($_GET['q'] ?? '');
    $cart_count = 0;
    foreach ($_SESSION['cart'] ?? [] as $cart_item) {
        $cart_count += (int) ($cart_item['quantity'] ?? 0);
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Khám phá và mua những cuốn sách hay tại BookStore.">
    <title>BookStore - Cửa Hàng Sách Online</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body>
<?php include 'store_header.php'; ?>

<main>
    <section class="store-hero">
        <div class="store-hero-inner">
            <div class="hero-copy">
                <span class="hero-kicker"><span></span> GÓC ĐỌC CỦA BẠN</span>
                <h1>Mở một cuốn sách.<br><em>Mở một thế giới.</em></h1>
                <p>Từ trang sách đầu tiên đến những ý tưởng ở lại thật lâu. Chọn cuốn tiếp theo cho hành trình của bạn.</p>
                <div class="hero-actions">
                    <a class="shop-primary-button" href="#featured-books">Khám phá sách <span aria-hidden="true">→</span></a>
                    <a class="shop-secondary-link" href="#categories">Chọn theo sở thích</a>
                </div>
                <div class="hero-note"><span aria-hidden="true">✦</span> Những câu chuyện đáng để mang về</div>
            </div>
            <div class="hero-visual" aria-label="Một vài cuốn sách tại BookStore">
                <div class="hero-shelf"></div>
                <img class="hero-cover hero-cover-back" src="images/nhagiakim.jpg" alt="Bìa sách Nhà Giả Kim">
                <img class="hero-cover hero-cover-side" src="images/1758266588_toithayhoavangtrencoxanh.jpg" alt="Bìa sách Tôi Thấy Hoa Vàng Trên Cỏ Xanh">
                <img class="hero-cover hero-cover-front" src="images/1758266396_dacnhantam.png" alt="Bìa sách Đắc Nhân Tâm">
                <span class="hero-stamp">ĐỌC<br>ĐỂ LỚN</span>
            </div>
        </div>
    </section>

    <section class="store-perks" aria-label="Thông tin cửa hàng">
        <div><span aria-hidden="true">◎</span><span><strong>Chọn sách dễ dàng</strong><small>Nhiều thể loại để khám phá</small></span></div>
        <div><span aria-hidden="true">▤</span><span><strong>Sách cho mọi lứa tuổi</strong><small>Từ giải trí đến phát triển bản thân</small></span></div>
        <div><span aria-hidden="true">♡</span><span><strong>Đặt sách thật nhanh</strong><small>Thêm vào giỏ chỉ với một chạm</small></span></div>
    </section>

    <div class="shop-container">
        <section class="category-section" id="categories">
            <div class="section-heading compact-heading">
                <div><span class="section-kicker">TÌM ĐÚNG GU ĐỌC</span><h2>Chọn một lối rẽ</h2></div>
                <p>Mỗi thể loại mở ra một thế giới khác.</p>
            </div>
            <nav class="category-list" aria-label="Danh mục sách">
                <a class="category-chip" href="index.php" <?= $cat === '' ? 'aria-current="page"' : '' ?>><span>✦</span>Tất cả sách</a>
                <a class="category-chip" href="index.php?cat=truyen-tranh" <?= $cat === 'truyen-tranh' ? 'aria-current="page"' : '' ?>><span>▧</span>Truyện tranh</a>
                <a class="category-chip" href="index.php?cat=van-hoc" <?= $cat === 'van-hoc' ? 'aria-current="page"' : '' ?>><span>✎</span>Văn học</a>
                <a class="category-chip" href="index.php?cat=kinh-te" <?= $cat === 'kinh-te' ? 'aria-current="page"' : '' ?>><span>↗</span>Kinh tế</a>
                <a class="category-chip" href="index.php?cat=tu-duy" <?= $cat === 'tu-duy' ? 'aria-current="page"' : '' ?>><span>◈</span>Tư duy - Kỹ năng</a>
            </nav>
        </section>

        <section class="catalog-section" id="featured-books">
            <div class="section-heading catalog-heading">
                <div><span class="section-kicker">GÓC SÁCH BOOKSTORE</span><h2><?= $q !== '' ? 'Kết quả tìm kiếm' : 'Có thể bạn sẽ thích' ?></h2><p><?= $q !== '' ? 'Đang tìm: “' . htmlspecialchars($q, ENT_QUOTES, 'UTF-8') . '”' : 'Chọn cuốn sách phù hợp cho lần đọc tiếp theo.' ?></p></div>
                <?php
                    $sql = "SELECT * FROM books WHERE 1";
                    if ($q !== '') {
                        $search = $conn->real_escape_string($q);
                        $sql .= " AND (title LIKE '%$search%' OR author LIKE '%$search%')";
                    }
                    if ($cat !== '') {
                        $category = $conn->real_escape_string($cat);
                        $sql .= " AND category='$category'";
                    }
                    $sql .= " ORDER BY id DESC";
                    $result = $conn->query($sql);
                ?>
                <?php if ($result): ?><span class="result-count"><?= number_format($result->num_rows) ?> cuốn sách</span><?php endif; ?>
            </div>

            <div class="product-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $image = !empty($row['image']) ? $row['image'] : 'image1.jpg';
                            $quantity = (int) $row['quantity'];
                        ?>
                        <article class="product-card">
                            <div class="product-cover">
                                <?php if ($quantity <= 0): ?><span class="product-badge sold-out">Tạm hết</span><?php elseif ($quantity <= 5): ?><span class="product-badge limited">Sắp hết</span><?php endif; ?>
                                <img src="images/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="Bìa sách <?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                            </div>
                            <div class="product-details">
                                <p class="product-author"><?= htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8') ?></p>
                                <h3><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <div class="product-meta"><strong><?= number_format($row['price'], 0, ',', '.') ?> ₫</strong><span><?= $quantity > 0 ? 'Còn ' . number_format($quantity) . ' cuốn' : 'Hết hàng' ?></span></div>
                                <?php if ($quantity > 0): ?>
                                    <a class="product-add-button" href="add_to_cart.php?id=<?= (int) $row['id'] ?>"><span aria-hidden="true">+</span> Thêm vào giỏ</a>
                                <?php else: ?>
                                    <span class="product-add-button is-disabled" aria-disabled="true">Tạm hết hàng</span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="catalog-empty"><span aria-hidden="true">⌕</span><h3>Chưa tìm thấy cuốn sách phù hợp</h3><p>Thử một từ khóa khác hoặc xem toàn bộ danh mục.</p><a href="index.php">Xem tất cả sách</a></div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<?php include 'store_footer.php'; ?>

</body>
</html>

