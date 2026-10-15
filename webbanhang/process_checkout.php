<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

if (empty($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$submitted_ids = $_POST['selected_ids'] ?? '';
$selected_ids = is_array($submitted_ids)
    ? $submitted_ids
    : explode(',', (string) $submitted_ids);
$selected_ids = array_unique(array_filter(array_map('intval', $selected_ids)));
$checkout_ids = [];
$total = 0;

foreach ($selected_ids as $id) {
    if (isset($_SESSION['cart'][$id])) {
        $checkout_ids[] = $id;
        $total += (float) $_SESSION['cart'][$id]['price'] * (int) $_SESSION['cart'][$id]['quantity'];
    }
}

if ($fullname === '' || $address === '' || $phone === '' || !$checkout_ids) {
    header('Location: cart.php');
    exit;
}

require 'db.php';
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, created_at) VALUES (?, ?, 'pending', NOW())");
if (!$stmt) {
    http_response_code(500);
    exit('Không thể tạo đơn hàng. Vui lòng thử lại sau.');
}

$user_id = (int) $_SESSION['user']['id'];
$stmt->bind_param('id', $user_id, $total);
if (!$stmt->execute()) {
    http_response_code(500);
    exit('Không thể tạo đơn hàng. Vui lòng thử lại sau.');
}

foreach ($checkout_ids as $id) {
    unset($_SESSION['cart'][$id]);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoàn tất đặt hàng</title>
    <link rel="stylesheet" href="style.css?v=4">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7f4;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            text-align: center;
            animation: fadeIn 0.6s ease;
        }
        h2 {
            font-size: 28px;
            color: #173f35;
            margin-bottom: 20px;
        }
        p {
            font-size: 17px;
            margin: 10px 0;
            color: #333;
        }
        .total {
            font-size: 22px;
            font-weight: bold;
            color: #c85e4c;
            margin: 20px 0;
        }
        .btn-back {
            display: inline-block;
            background: #173f35;
            color: #fff;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #24664e;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
            color: #ff9800;
        }
        @keyframes fadeIn {
            from {opacity:0; transform: translateY(20px);}
            to {opacity:1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <?php include 'store_header.php'; ?>
    <main class="checkout-main">
    <div class="container">
        <div class="icon">🎉</div>
        <h2>Cảm ơn <?= htmlspecialchars($fullname) ?> đã đặt hàng!</h2>
        <p>Đơn hàng sẽ được giao đến địa chỉ:</p>
        <p><strong><?= htmlspecialchars($address) ?></strong></p>
        <p>SĐT liên hệ: <strong><?= htmlspecialchars($phone) ?></strong></p>
        <div class="total">Tổng tiền: <?= number_format($total,0,',','.') ?> VND</div>
        <a href="cart.php" class="btn-back">⬅ Quay lại giỏ hàng</a>
    </div>
    </main>
    <?php include 'store_footer.php'; ?>
</body>
</html>
