<?php
require 'config.php';
if(!isset($_SESSION['user_id'])) header('Location: login.php');

$user_id = $_SESSION['user_id'];
$status_filter = $_GET['status'] ?? '';   // Lọc trạng thái
$expired_filter = $_GET['expired'] ?? ''; // Lọc quá hạn

$today = date('Y-m-d');

$sql = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$user_id];

// Lọc theo trạng thái
if($status_filter && in_array($status_filter, ['pending','in_progress','completed'])){
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}

// Lọc theo đã hết hạn / chưa hết hạn
if($expired_filter === 'yes'){
    $sql .= " AND due_date IS NOT NULL AND due_date < ?";
    $params[] = $today;
} elseif($expired_filter === 'no'){
    $sql .= " AND (due_date IS NULL OR due_date >= ?)";
    $params[] = $today;
}

$sql .= " ORDER BY due_date ASC"; // Sắp xếp theo ngày hết hạn
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h2>Chào, <?= htmlspecialchars($_SESSION['username']) ?></h2>
<a href="add_task.php" class="btn btn-success mb-3">Thêm công việc</a>
<a href="logout.php" class="btn btn-danger mb-3">Đăng xuất</a>

<form method="get" class="mb-3">
<!-- Lọc trạng thái -->
<select name="status" class="form-select" style="width:200px; display:inline-block;">
<option value="">Tất cả trạng thái</option>
<option value="pending" <?= $status_filter=='pending'?'selected':'' ?>>Chờ xử lý</option>
<option value="in_progress" <?= $status_filter=='in_progress'?'selected':'' ?>>Đang thực hiện</option>
<option value="completed" <?= $status_filter=='completed'?'selected':'' ?>>Hoàn thành</option>
</select>

<!-- Lọc đã hết hạn / chưa hết hạn -->
<select name="expired" class="form-select" style="width:200px; display:inline-block; margin-left:10px;">
<option value="">Tất cả</option>
<option value="yes" <?= $expired_filter=='yes'?'selected':'' ?>>Đã hết hạn</option>
<option value="no" <?= $expired_filter=='no'?'selected':'' ?>>Chưa hết hạn</option>
</select>

<button class="btn btn-primary">Lọc</button>
</form>

<table class="table table-bordered">
<tr>
<th>Tiêu đề</th>
<th>Mô tả</th>
<th>Trạng thái</th>
<th>Ngày tạo</th>
<th>Hạn </th>
<th>Hành động</th>
</tr>
<?php foreach($tasks as $task): ?>
<tr>
<td><?= htmlspecialchars($task['title']) ?></td>
<td><?= htmlspecialchars($task['description']) ?></td>
<td><?= $task['status'] ?></td>
<td><?= $task['created_at'] ?></td>
<td><?= $task['due_date'] ?: 'Chưa đặt hạn' ?></td>
<td>
<a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
<a href="delete_task.php?id=<?= $task['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
</td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
