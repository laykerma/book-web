<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['selected_ids'])) {
    header("Location: cart.php");
    exit;
}

$selected_ids = $_POST['selected_ids'];
$checkout_items = [];
$total = 0;

foreach ($selected_ids as $id) {
    if (isset($_SESSION['cart'][$id])) {
        $checkout_items[$id] = $_SESSION['cart'][$id];
        $total += $_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thanh toán</title>
<link rel="stylesheet" href="style.css?v=4">
<style>
    /* Tổng thể */
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: #f5f7f4;
        margin:0;
        padding:0;
        min-height:100vh;
        display:flex;
        flex-direction:column;
    }
    .container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        animation: fadeIn 0.6s ease;
    }
    h2 {
        text-align: center;
        color: #173f35;
        margin-bottom: 30px;
        font-size: 28px;
        letter-spacing: 1px;
    }

    /* Bảng sản phẩm */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }
    th {
        background: #173f35;
        color: #fff;
        padding: 14px 8px;
        font-weight: 600;
        text-transform: uppercase;
    }
    td {
        padding: 12px 8px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }
    tr:hover td { background: #f9f9f9; }

    /* Form */
    form {
        margin-top: 20px;
    }
    .form-group {
        margin-bottom: 18px;
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
    }
    input[type="text"], textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: border 0.3s, box-shadow 0.3s;
        font-size: 15px;
    }
    input[type="text"]:focus, textarea:focus {
        border-color: #24664e;
        box-shadow: 0 0 6px rgba(36,102,78,0.2);
        outline: none;
    }
    textarea {
        resize: vertical;
        min-height: 80px;
    }

    /* Nút */
    .btn-submit {
        display: inline-block;
        background: #173f35;
        color: #fff;
        padding: 14px 28px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 18px;
        font-weight: 600;
        transition: all 0.3s;
        width: 100%;
    }
    .btn-submit:hover {
        background: #24664e;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    }

    /* Tổng tiền */
    .total {
        text-align: right;
        font-size: 22px;
        font-weight: bold;
        margin-top: 10px;
        color: #c85e4c;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>
</head>
<body>
<?php include 'store_header.php'; ?>
<main class="checkout-main">
<div class="container">
    <h2>Thanh toán đơn hàng</h2>

    <table>
        <tr>
            <th>Sách</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
        <?php foreach ($checkout_items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['title']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['price'],0,',','.') ?> VND</td>
            <td><?= number_format($item['price'] * $item['quantity'],0,',','.') ?> VND</td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="total">Tổng tiền: <?= number_format($total,0,',','.') ?> VND</div>

    <form method="post" action="process_checkout.php">
        <input type="hidden" name="selected_ids" value="<?= htmlspecialchars(implode(',', $selected_ids)) ?>">

        <div class="form-group">
            <label>Họ và tên:</label>
            <input type="text" name="fullname" required>
        </div>

        <div class="form-group">
            <label>Địa chỉ giao hàng:</label>
            <textarea name="address" required></textarea>
        </div>

        <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" required>
        </div>

        <button type="submit" class="btn-submit">Xác nhận đặt hàng</button>
    </form>
</div>
</main>
<?php include 'store_footer.php'; ?>
</body>
</html>
