<?php
include 'config.php';
include 'includes/header.php';

$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id=$id";
$res = mysqli_query($conn, $sql);
$p = mysqli_fetch_assoc($res);

echo "
<h2>".$p['name']."</h2>
<img src='uploads/".$p['image']."' width='250'>
<p>Giá: ".number_format($p['price'])." đ</p>
<p>".$p['description']."</p>
<a href='cart.php?add=".$p['id']."' class='btn'>Thêm vào giỏ</a>
";

include 'includes/footer.php';
?>
