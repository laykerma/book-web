<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Lấy thông tin người dùng theo username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ✅ Kiểm tra mật khẩu bằng password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Chuyển hướng theo quyền
            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "❌ Mật khẩu không đúng!";
        }
    } else {
        $error = "❌ Không tồn tại tài khoản này!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - BookStore</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body class="store-page">
<?php include 'store_header.php'; ?>
<main class="account-main">
    <section class="account-panel">
        <h1>Đăng nhập</h1>
        <p class="account-intro">Đăng nhập để tiếp tục hành trình đọc của bạn.</p>
        <?php if (!empty($error)): ?><p class="form-feedback"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <form method="post" class="store-form">
            <label for="username">Tên đăng nhập</label>
            <input id="username" type="text" name="username" placeholder="Tên đăng nhập" required>
            <label for="password">Mật khẩu</label>
            <input id="password" type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng nhập</button>
        </form>
        <p class="account-switch">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
    </section>
</main>
<?php include 'store_footer.php'; ?>

</body>
</html>
