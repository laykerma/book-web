<?php 
include 'db.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if ($password !== $confirm) {
        echo "<script>alert('Mật khẩu nhập lại không khớp!');</script>";
    } else {
        // Hash mật khẩu
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Chuẩn bị SQL
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("ss", $username, $hash);

        if($stmt->execute()){
            echo "<script>alert('Đăng ký thành công! Hãy đăng nhập.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Tên đăng nhập đã tồn tại hoặc có lỗi xảy ra!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body class="store-page">
<?php include 'store_header.php'; ?>
<main class="account-main">
    <section class="account-panel">
        <h1>Tạo tài khoản</h1>
        <p class="account-intro">Tạo tài khoản để lưu lại những cuốn sách bạn yêu thích.</p>
        <form method="post" class="store-form">
            <label for="username">Tên đăng nhập</label>
            <input id="username" type="text" name="username" placeholder="Tên đăng nhập" required>
            <label for="password">Mật khẩu</label>
            <input id="password" type="password" name="password" placeholder="Mật khẩu" required>
            <label for="confirm">Nhập lại mật khẩu</label>
            <input id="confirm" type="password" name="confirm" placeholder="Nhập lại mật khẩu" required>
            <button type="submit">Tạo tài khoản</button>
        </form>
        <p class="account-switch">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </section>
</main>
<?php include 'store_footer.php'; ?>
</body>
</html>
