<?php
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Trang chủ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="#">📚 BookShop</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Xin chào, <?php echo $_SESSION['user']; ?>!</span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Đăng xuất</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2 class="mb-4">Danh sách sách</h2>
  <div class="row">
    <?php
    $result = $conn->query("SELECT * FROM books");
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4 mb-3">';
        echo '  <div class="card h-100 shadow-sm">';
        echo '    <div class="card-body">';
        echo '      <h5 class="card-title">'.$row['title'].'</h5>';
        echo '      <p class="card-text">Tác giả: '.$row['author'].'</p>';
        echo '      <p class="card-text text-danger fw-bold">'.number_format($row['price']).' VNĐ</p>';
        echo '    </div>';
        echo '    <div class="card-footer text-center">';
        echo '      <button class="btn btn-sm btn-primary">🛒 Thêm vào giỏ</button>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
    ?>
  </div>
</div>
</body>
</html>
