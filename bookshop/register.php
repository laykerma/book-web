<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        echo "<script>alert('Đăng ký thành công! Hãy đăng nhập.');window.location='login.php';</script>";
    } else {
        echo "<script>alert('Tên đăng nhập đã tồn tại!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4 shadow-lg" style="width: 400px;">
    <h3 class="text-center mb-3">📝 Đăng ký</h3>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Tên đăng nhập</label>
        <input class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <button class="btn btn-primary w-100">Đăng ký</button>
    </form>
    <div class="text-center mt-3">
      <a href="login.php">🔑 Đã có tài khoản? Đăng nhập</a>
    </div>
  </div>
</div>
</body>
</html>
