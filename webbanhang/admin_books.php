<?php
include 'db.php';
session_start();

// Kiểm tra nếu chưa đăng nhập admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Lấy danh sách tất cả sách
$result = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sách</title>
    <link rel="stylesheet" href="style.css?v=4">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        img {
            width: 50px;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-edit { background: #24664e; color: white; }
        .btn-delete { background: #c85e4c; color: white; }
        .btn-add { background: #173f35; color: white; margin-bottom: 10px; display: inline-block; }
    </style>
</head>
<body class="store-page">
    <?php include 'store_header.php'; ?>
    <main class="manage-main">
    <section class="manage-panel">
    <span class="section-kicker">QUẢN LÝ CỬA HÀNG</span>
    <h1>Quản lý sách</h1>
    <a href="add_book.php" class="btn btn-add">+ Thêm Sách Mới</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên Sách</th>
            <th>Tác Giả</th>
            <th>Giá</th>
            <th>Ảnh</th>
            <th>Hành động</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td><?= number_format($row['price']) ?> đ</td>
            <td><img src="images/<?= htmlspecialchars($row['image']) ?>" alt=""></td>
            <td>
                <a href="edit_book.php?id=<?= $row['id'] ?>" class="btn btn-edit">Sửa</a>
                <a href="delete_book.php?id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Xóa sách này?');">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </section>
    </main>
    <?php include 'store_footer.php'; ?>
</body>
</html>
