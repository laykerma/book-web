<?php
session_start();
include 'config.php';
$query = $conn->query("SELECT * FROM products LIMIT 8");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>ElectroShop - Điện thoại & Đồ điện tử</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
</head>

<body style="background:#f5f6fa;">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold fs-3" href="index.php">ElectroShop</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link px-3" href="index.php">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="products.php">Sản phẩm</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="cart.php">Giỏ hàng</a></li>

        <?php if(isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link px-3" href="#">Xin chào, <?= $_SESSION['user']; ?></a></li>
          <li class="nav-item"><a class="nav-link px-3" href="logout.php">Đăng xuất</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link px-3" href="login.php">Đăng nhập</a></li>
          <li class="nav-item"><a class="nav-link px-3" href="register.php">Đăng ký</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- BANNER -->
<section class="banner d-flex align-items-center justify-content-center text-center text-white">
  <div>
    <h1 class="fw-bold display-4">Cửa hàng điện tử hiện đại</h1>
    <p class="fs-5">Điện thoại - Laptop - Phụ kiện - Giá tốt mỗi ngày</p>
    <a href="products.php" class="btn btn-primary btn-lg px-4 mt-2">Mua ngay</a>
  </div>
</section>

<!-- DANH MỤC -->
<div class="container mt-5">
  <h2 class="fw-bold mb-4 text-center">Danh mục nổi bật</h2>
  <div class="row g-4">

    <div class="col-md-4">
      <div class="category-box p-4 shadow-sm rounded text-center bg-white">
        <h4>Điện thoại</h4>
      </div>
    </div>

    <div class="col-md-4">
      <div class="category-box p-4 shadow-sm rounded text-center bg-white">
        <h4>Laptop</h4>
      </div>
    </div>

    <div class="col-md-4">
      <div class="category-box p-4 shadow-sm rounded text-center bg-white">
        <h4>Phụ kiện</h4>
      </div>
    </div>

  </div>
</div>

<!-- SẢN PHẨM NỔI BẬT -->
<div class="container mt-5">
  <h2 class="fw-bold mb-4 text-center">Sản phẩm nổi bật</h2>
  <div class="row g-4">

    <?php while($row = $query->fetch_assoc()): ?>
    <div class="col-md-3">
      <div class="product-card shadow-sm rounded bg-white p-3 text-center">
        <img src="uploads/<?= $row['image']; ?>" class="product-img mb-3">
        <h5 class="fw-bold"><?= $row['name']; ?></h5>
        <p class="text-danger fw-bold"><?= number_format($row['price']); ?>₫</p>
        <a href="add_to_cart.php?id=<?= $row['id']; ?>" class="btn btn-dark w-100">Thêm vào giỏ</a>
      </div>
    </div>
    <?php endwhile; ?>

  </div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  © 2025 ElectroShop - Thiết kế bởi bạn
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
