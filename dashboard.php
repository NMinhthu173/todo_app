<?php
require 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) header('Location: login.php');

$user_id = $_SESSION['user_id'];
$status_filter = $_GET['status'] ?? '';
$expired_filter = $_GET['expired'] ?? '';

$today = date('Y-m-d');

$sql = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$user_id];

if ($status_filter && in_array($status_filter, ['pending', 'in_progress', 'completed'])) {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}

if ($expired_filter === 'yes') {
    $sql .= " AND due_date IS NOT NULL AND due_date < ?";
    $params[] = $today;
} elseif ($expired_filter === 'no') {
    $sql .= " AND (due_date IS NULL OR due_date >= ?)";
    $params[] = $today;
}

$sql .= " ORDER BY due_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách công việc</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f3f5ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            padding: 25px;
            border-radius: 10px;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.2);
        }

        .btn-purple {
            background: #6a11cb;
            color: white;
            border: none;
        }

        .btn-purple:hover {
            background: #520fa4;
            color: white;
        }

        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 8px;
            color: white;
            font-size: 13px;
        }

        .pending { background: #ff9b00; }
        .in_progress { background: #007bff; }
        .completed { background: #28a745; }
    </style>
</head>

<body class="container mt-4">

    <div class="header">
        <h2>👋 Xin chào, <b><?= htmlspecialchars($_SESSION['username']) ?></b></h2>
        <p>Chào mừng bạn trở lại với ToDo App!</p>
    </div>
    <h3 class="mb-3 text-primary fw-bold">📋 Danh sách công việc</h3>
    <div class="mb-3">
        <a href="add_task.php" class="btn btn-purple">➕ Thêm công việc</a>
        <a href="logout.php" class="btn btn-danger">Đăng xuất</a>
    </div>

  
    <form method="get" class="mb-4 d-flex gap-3">

        <select name="status" class="form-select" style="max-width:200px;">
            <option value="">Tất cả trạng thái</option>
            <option value="pending" <?= $status_filter=='pending' ? 'selected' : '' ?>>Chờ xử lý</option>
            <option value="in_progress" <?= $status_filter=='in_progress' ? 'selected' : '' ?>>Đang thực hiện</option>
            <option value="completed" <?= $status_filter=='completed' ? 'selected' : '' ?>>Hoàn thành</option>
        </select>

        <select name="expired" class="form-select" style="max-width:200px;">
            <option value="">Tất cả hạn</option>
            <option value="yes" <?= $expired_filter=='yes' ? 'selected' : '' ?>>Đã hết hạn</option>
            <option value="no" <?= $expired_filter=='no' ? 'selected' : '' ?>>Chưa hết hạn</option>
        </select>

        <button class="btn btn-primary">Lọc</button>
    </form>

   
    <table class="table table-bordered table-hover">
        <tr class="table-secondary">
            <th>Tiêu đề</th>
            <th>Mô tả</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Hạn</th>
            <th>Hành động</th>
        </tr>

        <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?= htmlspecialchars($task['title']) ?></td>

            <td><?= htmlspecialchars($task['description']) ?></td>

            <td>
                <span class="badge-status <?= $task['status'] ?>">
                    <?= $task['status']=='pending' ? 'Chờ xử lý' : '' ?>
                    <?= $task['status']=='in_progress' ? 'Đang thực hiện' : '' ?>
                    <?= $task['status']=='completed' ? 'Hoàn thành' : '' ?>
                </span>
            </td>

            <td><?= date("d/m/Y", strtotime($task['created_at'])) ?></td>

            <td>
                <?= $task['due_date'] ? $task['due_date'] : "<i>Chưa đặt hạn</i>" ?>
            </td>

            <td>
                <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                <a href="delete_task.php?id=<?= $task['id'] ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Bạn có chắc muốn xóa?')">
                   Xóa
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
