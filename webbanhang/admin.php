<?php
session_start();
include 'db.php';

// ✅ Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// ✅ Chỉ admin mới được vào
if ($_SESSION['user']['role'] !== 'admin') {
    echo "🚫 Bạn không có quyền truy cập trang quản trị.";
    exit;
}

if (isset($_GET['orders_json'])) {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');

    $orders = $conn->query("SELECT o.id, o.user_id, u.username, o.total_price, o.status, o.created_at
                            FROM orders o
                            JOIN users u ON o.user_id = u.id
                            ORDER BY o.id DESC");
    if (!$orders) {
        http_response_code(500);
        echo json_encode(['error' => 'Không thể tải danh sách đơn hàng.']);
        exit;
    }

    echo json_encode($orders->fetch_all(MYSQLI_ASSOC), JSON_UNESCAPED_UNICODE);
    exit;
}

// ✅ Truy vấn danh sách Sách
$sql_books = "SELECT id, title, author, price, quantity FROM books ORDER BY id DESC";
$result_books = $conn->query($sql_books);

// ✅ Truy vấn danh sách Người dùng
$sql_users = "SELECT id, username, email, role, created_at FROM users ORDER BY id DESC";
$result_users = $conn->query($sql_users);

// ✅ Truy vấn danh sách Đơn hàng (nếu có bảng orders)
$sql_orders = "SELECT o.id, o.user_id, u.username, o.total_price, o.status, o.created_at
               FROM orders o
               JOIN users u ON o.user_id = u.id
               ORDER BY o.id DESC";
$result_orders = $conn->query($sql_orders);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Trang Quản Trị - BookStore</title>
<link rel="stylesheet" href="style.css?v=4">
<style>
:root {
    color-scheme: light;
    --forest: #173f35;
    --forest-soft: #235849;
    --mint: #e7f2ec;
    --lime: #e6bd68;
    --canvas: #f3f6f3;
    --paper: #ffffff;
    --ink: #202b27;
    --muted: #75817b;
    --line: #e3e9e5;
    --blue: #24664e;
    --red: #c85e4c;
}
* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
    min-height: 100vh;
    margin: 0;
    background: var(--canvas);
    color: var(--ink);
    font-family: "Segoe UI", Tahoma, sans-serif;
    font-size: 14px;
}
a { color: inherit; }
.app-shell { min-height: 100vh; }
.admin-footer-wrap { margin-left: 248px; }
.sidebar {
    position: fixed;
    inset: 78px auto 0 0;
    z-index: 2;
    display: flex;
    width: 248px;
    flex-direction: column;
    padding: 26px 18px 20px;
    background: var(--forest);
    color: #f4f8f5;
}
.brand { 
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 10px 42px;
    color: white;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 20px;
    font-weight: 700;
    text-decoration: none;
}
.brand-mark {
    display: grid;
    width: 38px;
    height: 38px;
    place-items: center;
    border-radius: 11px;
    background: var(--lime);
    color: var(--forest);
    font-family: "Segoe UI", sans-serif;
    font-size: 19px;
}
.nav-label {
    margin: 0 12px 11px;
    color: #9eb8ac;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}
.side-nav { display: grid; gap: 5px; }
.side-nav a, .sidebar-footer a {
    display: flex;
    min-height: 43px;
    align-items: center;
    gap: 12px;
    padding: 0 12px;
    border-radius: 8px;
    color: #d7e4dd;
    font-weight: 600;
    text-decoration: none;
    transition: background .18s ease, color .18s ease;
}
.side-nav a:hover, .side-nav a.active {
    background: rgba(255,255,255,.12);
    color: white;
}
.side-nav a.active { box-shadow: inset 3px 0 var(--lime); }
.nav-icon { width: 20px; text-align: center; font-size: 16px; }
.sidebar-footer { margin-top: auto; padding-top: 18px; border-top: 1px solid rgba(255,255,255,.14); }
.sidebar-footer a.logout { color: #ffd0ca; }
.sidebar-footer a:hover { background: rgba(255,255,255,.1); }
.main-area { min-height: 100vh; margin-left: 248px; }
.topbar {
    display: flex;
    min-height: 72px;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 0 42px;
    border-bottom: 1px solid var(--line);
    background: rgba(255,255,255,.88);
}
.breadcrumb { color: var(--muted); }
.breadcrumb strong { color: var(--ink); font-weight: 650; }
.admin-chip {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 7px 11px;
    border: 1px solid var(--line);
    border-radius: 999px;
    background: white;
    font-size: 12px;
    font-weight: 700;
}
.admin-dot { width: 8px; height: 8px; border-radius: 50%; background: #5da879; }
.content { width: min(100%, 1440px); margin: 0 auto; padding: 36px 42px 60px; }
.welcome-row { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 27px; }
.eyebrow { margin: 0 0 8px; color: var(--forest-soft); font-size: 11px; font-weight: 800; letter-spacing: 1.3px; text-transform: uppercase; }
.welcome-row h1 { margin: 0; color: var(--forest); font-family: "Segoe UI", Tahoma, sans-serif; font-size: clamp(27px, 3vw, 36px); line-height: 1.15; }
.welcome-row p { margin: 9px 0 0; color: var(--muted); }
.date-label { color: var(--muted); font-size: 12px; white-space: nowrap; }
.stats-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-bottom: 34px; }
.stat-card {
    position: relative;
    overflow: hidden;
    min-height: 125px;
    padding: 20px 22px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}
.stat-card::after { position: absolute; top: 0; right: 0; width: 5px; height: 100%; background: var(--lime); content: ""; }
.stat-top { display: flex; justify-content: space-between; align-items: center; color: var(--muted); font-size: 12px; font-weight: 650; }
.stat-icon { display: grid; width: 34px; height: 34px; place-items: center; border-radius: 9px; background: var(--mint); color: var(--forest); font-size: 16px; }
.stat-number { margin-top: 9px; color: var(--forest); font-size: 28px; font-weight: 750; line-height: 1; }
.section { margin-top: 28px; scroll-margin-top: 24px; }
.section-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 13px; }
.section-title { display: flex; align-items: center; gap: 11px; }
.section-icon { display: grid; width: 36px; height: 36px; place-items: center; border-radius: 9px; background: var(--mint); color: var(--forest); font-size: 17px; }
.section-title h2 { margin: 0; color: var(--forest); font-family: "Segoe UI", Tahoma, sans-serif; font-size: 21px; }
.section-subtitle { margin: 3px 0 0; color: var(--muted); font-size: 12px; }
.primary-link {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 13px;
    border-radius: 7px;
    background: var(--forest);
    color: white;
    font-weight: 700;
    text-decoration: none;
    transition: background .18s ease, transform .18s ease;
}
.primary-link:hover { transform: translateY(-1px); background: var(--forest-soft); }
.table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 9px; background: var(--paper); }
table { width: 100%; min-width: 700px; border-collapse: collapse; }
th, td { padding: 12px 15px; border-bottom: 1px solid var(--line); text-align: left; white-space: nowrap; }
th { background: #f7f9f7; color: #78847d; font-size: 10px; font-weight: 800; letter-spacing: .7px; text-transform: uppercase; }
td { color: #35413b; font-size: 13px; }
tbody tr:last-child td { border-bottom: 0; }
tbody tr:hover { background: #f8fbf8; }
td.numeric, th.numeric { text-align: right; }
.book-title { color: var(--ink); font-weight: 700; }
.table-id { color: #8a9690; font-variant-numeric: tabular-nums; }
.stock-pill, .role-pill, .status {
    display: inline-flex;
    align-items: center;
    padding: 4px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 750;
}
.stock-pill { background: #edf5e4; color: #50712e; }
.role-pill { background: #edf1f8; color: #465f8b; }
.status { background: #fff2d8; color: #8b6411; }
.status.delivered { background: #e4f3e9; color: #34754a; }
.status.cancelled { background: #fce9e7; color: #a6413a; }
.actions { display: flex; justify-content: flex-end; gap: 7px; }
.btn { display: inline-flex; align-items: center; justify-content: center; min-height: 29px; padding: 0 10px; border: 1px solid transparent; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none; transition: background .18s ease; }
.btn-edit { border-color: #d9e5fa; background: #edf3ff; color: var(--blue); }
.btn-edit:hover { background: #dfeaff; }
.btn-delete { border-color: #f4dcda; background: #fff1ef; color: var(--red); }
.btn-delete:hover { background: #ffe3df; }
.btn-view { border-color: #d8e9df; background: #edf6f0; color: var(--forest-soft); }
.btn-view:hover { background: #dfefe5; }
.empty-state { padding: 28px; color: var(--muted); text-align: center; }
@media (max-width: 900px) {
    .sidebar { width: 210px; }
    .main-area { margin-left: 210px; }
    .content { padding: 30px 24px 48px; }
    .topbar { padding: 0 24px; }
}
@media (max-width: 680px) {
    .sidebar { position: static; width: auto; padding: 12px 16px; }
    .admin-footer-wrap { margin-left: 0; }
    .brand { margin: 0 0 13px; font-size: 18px; }
    .nav-label { display: none; }
    .side-nav { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .side-nav a { min-height: 38px; justify-content: center; gap: 6px; padding: 0 6px; font-size: 11px; }
    .nav-icon { width: auto; }
    .sidebar-footer { display: flex; justify-content: flex-end; gap: 5px; margin-top: 8px; padding-top: 8px; }
    .sidebar-footer a { min-height: 32px; padding: 0 9px; font-size: 11px; }
    .main-area { margin-left: 0; }
    .topbar { min-height: 54px; padding: 0 18px; }
    .content { padding: 27px 16px 40px; }
    .welcome-row { align-items: flex-start; flex-direction: column; gap: 10px; }
    .stats-grid { grid-template-columns: 1fr; gap: 10px; margin-bottom: 27px; }
    .stat-card { min-height: 105px; padding: 15px 18px; }
    .stat-number { font-size: 25px; }
    .section-head { align-items: flex-start; }
    .section-title h2 { font-size: 19px; }
    .primary-link { flex-shrink: 0; padding: 8px 10px; font-size: 12px; }
    th, td { padding: 11px 12px; }
}
</style>
</head>
<body>
<?php include 'store_header.php'; ?>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="admin.php"><span class="brand-mark">B</span><span>BookStore<br><small style="font-family:'Segoe UI',sans-serif;font-size:10px;font-weight:500;color:#b8cec2">ADMIN PANEL</small></span></a>
        <p class="nav-label">Quản lý cửa hàng</p>
        <nav class="side-nav" aria-label="Điều hướng quản trị">
            <a class="active" href="#overview"><span class="nav-icon">⌂</span>Tổng quan</a>
            <a href="#books"><span class="nav-icon">▤</span>Sách</a>
            <a href="#users"><span class="nav-icon">♙</span>Người dùng</a>
            <a href="#orders"><span class="nav-icon">▣</span>Đơn hàng</a>
        </nav>
        <div class="sidebar-footer">
            <a href="index.php"><span class="nav-icon">↗</span>Về cửa hàng</a>
            <a class="logout" href="logout.php"><span class="nav-icon">⇥</span>Đăng xuất</a>
        </div>
    </aside>

    <main class="main-area" id="overview">
        <div class="topbar">
            <div class="breadcrumb">Cửa hàng <span aria-hidden="true">/</span> <strong>Quản trị</strong></div>
            <div class="admin-chip"><span class="admin-dot"></span><?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Admin') ?></div>
        </div>
        <div class="content">
            <div class="welcome-row">
                <div>
                    <p class="eyebrow">Bảng điều khiển</p>
                    <h1>Xin chào, <?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Admin') ?></h1>
                    <p>Đây là tình hình hoạt động của cửa hàng sách.</p>
                </div>
                <div class="date-label"><?= date('d/m/Y') ?></div>
            </div>

            <div class="stats-grid" aria-label="Tổng quan dữ liệu">
                <article class="stat-card">
                    <div class="stat-top"><span>Tổng đầu sách</span><span class="stat-icon">▤</span></div>
                    <div class="stat-number"><?= $result_books ? number_format($result_books->num_rows) : '0' ?></div>
                </article>
                <article class="stat-card">
                    <div class="stat-top"><span>Tài khoản người dùng</span><span class="stat-icon">♙</span></div>
                    <div class="stat-number"><?= $result_users ? number_format($result_users->num_rows) : '0' ?></div>
                </article>
                <article class="stat-card">
                    <div class="stat-top"><span>Đơn hàng</span><span class="stat-icon">▣</span></div>
                    <div class="stat-number" id="orders-count"><?= $result_orders ? number_format($result_orders->num_rows) : '0' ?></div>
                </article>
            </div>

    <!-- ===================== QUẢN LÝ SÁCH ===================== -->
    <section class="section" id="books">
    <div class="section-head">
        <div class="section-title"><span class="section-icon">▤</span><div><h2>Danh sách sách</h2><p class="section-subtitle">Quản lý kho sách và thông tin xuất bản</p></div></div>
        <a class="primary-link" href="add_book.php"><span aria-hidden="true">+</span> Thêm sách</a>
    </div>
    <div class="table-wrap"><table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Tác giả</th>
            <th class="numeric">Giá bán</th>
            <th class="numeric">Tồn kho</th>
            <th class="numeric">Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_books && $result_books->num_rows > 0): ?>
            <?php while ($book = $result_books->fetch_assoc()): ?>
                <tr>
                    <td class="table-id">#<?= $book['id'] ?></td>
                    <td class="book-title"><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td class="numeric"><?= number_format($book['price'], 0, ',', '.') ?> ₫</td>
                    <td class="numeric"><span class="stock-pill"><?= number_format($book['quantity']) ?> cuốn</span></td>
                    <td class="numeric">
                        <div class="actions">
                        <a class="btn btn-edit" href="edit_book.php?id=<?= $book['id'] ?>">Sửa</a>
                        <a class="btn btn-delete" href="delete_book.php?id=<?= $book['id'] ?>" onclick="return confirm('Xóa sách này?')">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td class="empty-state" colspan="6">Chưa có sách nào trong cửa hàng.</td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
    </section>

    <!-- ===================== QUẢN LÝ NGƯỜI DÙNG ===================== -->
    <section class="section" id="users">
    <div class="section-head"><div class="section-title"><span class="section-icon">♙</span><div><h2>Người dùng</h2><p class="section-subtitle">Các tài khoản đã đăng ký</p></div></div></div>
    <div class="table-wrap"><table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_users && $result_users->num_rows > 0): ?>
            <?php while ($user = $result_users->fetch_assoc()): ?>
                <tr>
                    <td class="table-id">#<?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><span class="role-pill"><?= htmlspecialchars($user['role']) ?></span></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td class="empty-state" colspan="5">Chưa có tài khoản người dùng.</td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
    </section>

    <!-- ===================== QUẢN LÝ ĐƠN HÀNG ===================== -->
    <section class="section" id="orders">
    <div class="section-head"><div class="section-title"><span class="section-icon">▣</span><div><h2>Đơn hàng</h2><p class="section-subtitle">Tự cập nhật mỗi 5 giây</p></div></div></div>
    <div class="table-wrap"><table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th class="numeric">Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th class="numeric">Thao tác</th>
        </tr>
        </thead>
        <tbody id="orders-body">
        <?php if ($result_orders && $result_orders->num_rows > 0): ?>
            <?php while ($order = $result_orders->fetch_assoc()): ?>
                <tr>
                    <td class="table-id">#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td class="numeric"><?= number_format($order['total_price'], 0, ',', '.') ?> ₫</td>
                    <td>
                        <?php
                            $statusClass = 'pending';
                            if ($order['status'] == 'delivered') $statusClass = 'delivered';
                            elseif ($order['status'] == 'cancelled') $statusClass = 'cancelled';
                        ?>
                        <span class="status <?= $statusClass ?>">
                            <?= htmlspecialchars($order['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                    <td class="numeric">
                        <a class="btn btn-view" href="view_order.php?id=<?= $order['id'] ?>">Xem</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td class="empty-state" colspan="6">Chưa có đơn hàng nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
    </section>
        </div>
    </main>
</div>
<div class="admin-footer-wrap">
    <?php include 'store_footer.php'; ?>
</div>
<script>
const ordersBody = document.getElementById('orders-body');
const ordersCount = document.getElementById('orders-count');

function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, character => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[character]);
}

async function refreshOrders() {
    try {
        const response = await fetch('admin.php?orders_json=1', { cache: 'no-store' });
        if (!response.ok) return;

        const orders = await response.json();
        ordersCount.textContent = new Intl.NumberFormat('vi-VN').format(orders.length);
        if (orders.length === 0) {
            ordersBody.innerHTML = '<tr><td class="empty-state" colspan="6">Chưa có đơn hàng nào.</td></tr>';
            return;
        }

        ordersBody.innerHTML = orders.map(order => {
            const status = String(order.status);
            const statusClass = ['delivered', 'cancelled'].includes(status) ? status : 'pending';
            return `<tr>
                <td class="table-id">#${escapeHtml(order.id)}</td>
                <td>${escapeHtml(order.username)}</td>
                <td class="numeric">${new Intl.NumberFormat('vi-VN').format(Number(order.total_price))} ₫</td>
                <td><span class="status ${statusClass}">${escapeHtml(status)}</span></td>
                <td>${escapeHtml(order.created_at)}</td>
                <td class="numeric"><a class="btn btn-view" href="view_order.php?id=${encodeURIComponent(order.id)}">Xem</a></td>
            </tr>`;
        }).join('');
    } catch (error) {
        // Keep the server-rendered order list visible if a refresh fails.
    }
}

window.setInterval(refreshOrders, 5000);
</script>
</body>
</html>
