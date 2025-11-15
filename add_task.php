<?php
require 'config.php';
if(!isset($_SESSION['user_id'])) header('Location: login.php');

if($_SERVER['REQUEST_METHOD']==='POST'){
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$due_date = !empty($_POST['due_date'])?$_POST['due_date']:null;
$status = 'pending';

if(!empty($title)){
$stmt = $pdo->prepare("INSERT INTO tasks (user_id,title,description,due_date,status) VALUES (?,?,?,?,?)");
$stmt->execute([$_SESSION['user_id'],$title,$description,$due_date,$status]);
header('Location: dashboard.php'); exit();
}else $error="Tiêu đề công việc không được để trống!";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm công việc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
        }
        .task-card {
            max-width: 650px;
            margin: 50px auto;
            border-radius: 12px;
            padding: 30px;
            background: #fff;
            box-shadow: 0 4px 18px rgba(0,0,0,0.1);
        }
        .btn-custom {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
        }
        h2 {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="task-card">
        <h2 class="text-center mb-4">📝 Thêm công việc mới</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">

            <div class="mb-3">
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg" placeholder="Nhập tên công việc" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Mô tả chi tiết (không bắt buộc)"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày hết hạn</label>
                <input type="date" name="due_date" class="form-control">
            </div>

            <button class="btn btn-success btn-custom mb-2">➕ Thêm công việc</button>
            <a href="dashboard.php" class="btn btn-secondary btn-custom">⬅ Quay lại</a>

        </form>
    </div>
</body>
</html>

