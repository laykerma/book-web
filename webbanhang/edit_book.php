<?php
$conn = new mysqli("localhost", "root", "", "bookstore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$msg = "";

if (!isset($_GET['id'])) {
    die("❌ Thiếu ID sách!");
}

$id = (int)$_GET['id'];

// Lấy thông tin sách hiện tại
$sql = "SELECT * FROM books WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("❌ Không tìm thấy sách!");
}
$book = $result->fetch_assoc();

// Nếu submit form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];

    $imageName = $book['image']; // giữ ảnh cũ mặc định

    // Nếu upload ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "images/";
        $tmpName = $_FILES['image']['tmp_name'];
        $originalName = basename($_FILES['image']['name']);
        $newImageName = time() . "_" . $originalName;

        if (move_uploaded_file($tmpName, $uploadDir . $newImageName)) {
            // Xóa ảnh cũ nếu không phải default
            if ($book['image'] !== "default.jpg" && file_exists("images/" . $book['image'])) {
                unlink("images/" . $book['image']);
            }
            $imageName = $newImageName;
        } else {
            $msg = "❌ Lỗi khi upload ảnh mới.";
        }
    }

    // Cập nhật DB
    $sqlUpdate = "UPDATE books 
                  SET title='$title', author='$author', price=$price, quantity=$quantity, image='$imageName' 
                  WHERE id=$id";

    if ($conn->query($sqlUpdate) === TRUE) {
        // ✅ Sau khi cập nhật thành công → quay về Trang chủ
        header("Location: index.php?msg=updated");
        exit;
    } else {
        $msg = "❌ Lỗi SQL: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sách</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body class="store-page">
    <?php include 'store_header.php'; ?>
    <main class="manage-main">
    <section class="manage-panel">
    <span class="section-kicker">QUẢN LÝ CỬA HÀNG</span>
    <h1>Sửa thông tin sách</h1>
    <?php if (!empty($msg)) echo "<p class='form-feedback'>$msg</p>"; ?>

    <form method="post" enctype="multipart/form-data" class="store-form">
        <label>Tên sách:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

        <label>Tác giả:</label>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>

        <label>Giá:</label>
        <input type="number" step="0.01" name="price" value="<?= $book['price'] ?>" required>

        <label>Số lượng:</label>
        <input type="number" name="quantity" value="<?= $book['quantity'] ?>" min="1" required>

        <label>Ảnh hiện tại:</label>
        <img src="images/<?= htmlspecialchars($book['image']) ?>" alt="Ảnh sách">

        <label>Chọn ảnh mới (nếu muốn đổi):</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">💾 Lưu thay đổi</button>
    </form>

    <div class="manage-links">
        <a href="index.php">🏠 Về Trang chủ</a> | 
        <a href="admin_books.php">📚 Quản lý sách</a>
    </div>
    </section>
    </main>
    <?php include 'store_footer.php'; ?>
</body>
</html>
