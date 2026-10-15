<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body class="cart-page">
<?php include 'store_header.php'; ?>

<main class="cart-main">
    <div class="cart-heading">
        <div>
            <span class="section-kicker">BOOKSTORE / ĐƠN HÀNG</span>
            <h1>Giỏ hàng</h1>
        </div>
        <span class="cart-item-count"><?= $cart_count ?> sản phẩm</span>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="cart-columns">
            <section class="cart-items" aria-label="Sản phẩm trong giỏ hàng">
                <div class="cart-list-heading">
                    <label class="cart-select-all"><input type="checkbox" id="select-all"> Chọn tất cả</label>
                    <span>Sản phẩm</span>
                    <span>Số lượng</span>
                    <span>Thành tiền</span>
                </div>

                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                    <?php $line_total = (float) $item['price'] * (int) $item['quantity']; ?>
                    <article class="cart-item" data-total="<?= $line_total ?>">
                        <div class="cart-select-cell">
                            <input type="checkbox" class="select-item" data-id="<?= (int) $id ?>" aria-label="Chọn <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="cart-product">
                            <span class="cart-product-mark" aria-hidden="true">B</span>
                            <div>
                                <h2><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                                <p><?= number_format((float) $item['price'], 0, ',', '.') ?> ₫ / cuốn</p>
                            </div>
                        </div>
                        <form method="post" action="update_cart.php" class="cart-quantity">
                            <input type="hidden" name="id" value="<?= (int) $id ?>">
                            <button type="submit" name="action" value="decrease" aria-label="Giảm số lượng">−</button>
                            <input type="number" name="quantity" value="<?= (int) $item['quantity'] ?>" min="1" aria-label="Số lượng">
                            <button type="submit" name="action" value="increase" aria-label="Tăng số lượng">+</button>
                        </form>
                        <div class="cart-line-total">
                            <strong><?= number_format($line_total, 0, ',', '.') ?> ₫</strong>
                            <a href="remove_from_cart.php?id=<?= (int) $id ?>" aria-label="Xóa <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?> khỏi giỏ hàng">Xóa</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <aside class="cart-summary" aria-label="Tóm tắt đơn hàng">
                <h2>Tóm tắt đơn hàng</h2>
                <div class="cart-summary-line"><span>Sản phẩm đã chọn</span><strong id="selected-count">0</strong></div>
                <div class="cart-summary-total"><span>Tạm tính</span><strong id="selected-total">0 ₫</strong></div>
                <p>Phí vận chuyển được tính ở bước tiếp theo.</p>
                <form id="checkout-form" method="post" action="checkout.php"></form>
                <button id="checkout-btn" class="cart-checkout-button" type="button">Tiến hành thanh toán <span aria-hidden="true">→</span></button>
                <a class="cart-back-link" href="index.php">← Tiếp tục chọn sách</a>
            </aside>
        </div>
    <?php else: ?>
        <section class="cart-empty">
            <span aria-hidden="true">▤</span>
            <h2>Giỏ hàng đang trống</h2>
            <p>Chọn một cuốn sách hay để bắt đầu hành trình đọc tiếp theo.</p>
            <a href="index.php">Khám phá sách <span aria-hidden="true">→</span></a>
        </section>
    <?php endif; ?>
</main>

<?php include 'store_footer.php'; ?>

<script>
const selectAll = document.getElementById("select-all");
const itemCheckboxes = Array.from(document.querySelectorAll(".select-item"));
const selectedCount = document.getElementById("selected-count");
const selectedTotal = document.getElementById("selected-total");
const formatCurrency = value => new Intl.NumberFormat("vi-VN").format(value) + " ₫";

function updateCartSummary() {
    const checkedItems = itemCheckboxes.filter(checkbox => checkbox.checked);
    const total = checkedItems.reduce((sum, checkbox) => sum + Number(checkbox.closest(".cart-item").dataset.total), 0);
    selectedCount.textContent = checkedItems.length;
    selectedTotal.textContent = formatCurrency(total);
    selectAll.checked = itemCheckboxes.length > 0 && checkedItems.length === itemCheckboxes.length;
}

selectAll?.addEventListener("change", function(event) {
    itemCheckboxes.forEach(checkbox => checkbox.checked = event.target.checked);
    updateCartSummary();
});

itemCheckboxes.forEach(checkbox => checkbox.addEventListener("change", updateCartSummary));

document.getElementById("checkout-btn")?.addEventListener("click", function() {
    const checked = itemCheckboxes.filter(checkbox => checkbox.checked).map(checkbox => checkbox.dataset.id);
    if (checked.length === 0) {
        alert("Vui lòng chọn ít nhất 1 sản phẩm để thanh toán.");
        return;
    }

    const checkoutForm = document.getElementById("checkout-form");
    checkoutForm.replaceChildren();
    checked.forEach(id => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "selected_ids[]";
        input.value = id;
        checkoutForm.appendChild(input);
    });
    checkoutForm.submit();
});

if (selectedCount && selectedTotal) {
    updateCartSummary();
}
</script>
</body>
</html>
