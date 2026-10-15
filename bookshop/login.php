<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $_SESSION['user'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Sai tài khoản hoặc mật khẩu!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4 shadow-lg" style="width: 400px;">
    <h3 class="text-center mb-3">🔑 Đăng nhập</h3>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Tên đăng nhập</label>
        <input class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <button class="btn btn-success w-100">Đăng nhập</button>
    </form>
    <div class="text-center mt-3">
      <a href="register.php">📝 Chưa có tài khoản? Đăng ký</a>
    </div>
  </div>
</div>
</body>
</html>
