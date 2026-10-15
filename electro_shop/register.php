<?php
include 'config.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(username, password) VALUES ('$username', '$pass')";

    if (mysqli_query($conn, $sql)) {
        $msg = "Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a>";
    } else {
        $msg = "Lỗi: " . mysqli_error($conn);
    }
}
?>

<div class="form-box">
    <h2>Đăng ký</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng ký</button>
    </form>
    <p style="color: green;"><?= $msg ?></p>
</div>
