<?php
session_start();
include 'config.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u = $_POST["username"];
    $p = $_POST["password"];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$u'");
    $user = mysqli_fetch_assoc($res);

    if ($user && password_verify($p, $user["password"])) {
        $_SESSION["user"] = $user;
        header("Location: index.php");
        exit;
    } else {
        $msg = "Sai tài khoản hoặc mật khẩu!";
    }
}
?>

<div class="form-box">
    <h2>Đăng nhập</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>
    <p style="color: red;"><?= $msg ?></p>
</div>
