<?php
$conn = new mysqli("localhost", "root", "", "bookstore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Lấy tên ảnh để xóa file vật lý nếu có
    $sql = "SELECT image FROM books WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['image'];

        // Xóa bản ghi trong DB
        $sqlDelete = "DELETE FROM books WHERE id = $id";
        if ($conn->query($sqlDelete) === TRUE) {
            // Nếu ảnh tồn tại thì xóa luôn file
            if (!empty($image) && file_exists("images/".$image)) {
                unlink("images/".$image);
            }

            // Chuyển hướng về admin_books với thông báo
            header("Location: admin_books.php?msg=" . urlencode("Xóa thành công"));
            exit;
        } else {
            echo "❌ Lỗi khi xóa: " . $conn->error;
        }
    } else {
        echo "❌ Không tìm thấy sách với ID = $id";
    }
} else {
    echo "❌ Thiếu tham số ID!";
}
