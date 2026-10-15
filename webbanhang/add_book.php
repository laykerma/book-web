<?php
$conn = new mysqli("localhost", "root", "", "bookstore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];

    // Xử lý ảnh upload
    $imageName = "default.jpg"; // nếu không chọn ảnh thì mặc định

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "images/";
        $tmpName = $_FILES['image']['tmp_name'];
        $originalName = basename($_FILES['image']['name']);
        $imageName = time() . "_" . $originalName; // tránh trùng tên

        if (move_uploaded_file($tmpName, $uploadDir . $imageName)) {
            // upload thành công
        } else {
            $msg = "❌ Lỗi khi upload ảnh.";
        }
    }

    // Thêm vào CSDL
    $sql = "INSERT INTO books (title, author, price, quantity, image) 
            VALUES ('$title', '$author', $price, $quantity, '$imageName')";

    if ($conn->query($sql) === TRUE) {
        $msg = "✅ Thêm sách thành công!";
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
    <title>Thêm sách</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body class="store-page">
    <?php include 'store_header.php'; ?>
    <main class="manage-main">
    <section class="manage-panel">
    <span class="section-kicker">QUẢN LÝ CỬA HÀNG</span>
    <h1>Thêm sách mới</h1>
    <?php if (!empty($msg)) echo "<p class='form-feedback'>$msg</p>"; ?>

    <form method="post" enctype="multipart/form-data" class="store-form">
        <label>Tên sách:</label>
        <input type="text" name="title" required>

        <label>Tác giả:</label>
        <input type="text" name="author" required>

        <label>Giá:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Số lượng:</label>
        <input type="number" name="quantity" required>

        <label>Ảnh bìa:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Thêm sách</button>
    </form>

    <p class="account-switch"><a href="admin_books.php">← Quay lại danh sách sách</a></p>
    </section>
    </main>
    <?php include 'store_footer.php'; ?>
</body>
</html>
