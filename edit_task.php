<?php
require 'config.php';
if(!isset($_SESSION['user_id'])) header('Location: login.php');

$id=$_GET['id']??null;
if(!$id) header('Location: dashboard.php');

$stmt=$pdo->prepare("SELECT * FROM tasks WHERE id=? AND user_id=?");
$stmt->execute([$id,$_SESSION['user_id']]);
$task=$stmt->fetch();
if(!$task) header('Location: dashboard.php');

if($_SERVER['REQUEST_METHOD']==='POST'){
$title=trim($_POST['title']);
$description=trim($_POST['description']);
$due_date=!empty($_POST['due_date'])?$_POST['due_date']:null;
$status=$_POST['status'];

if(!empty($title) && in_array($status,['pending','in_progress','completed'])){
$stmt=$pdo->prepare("UPDATE tasks SET title=?,description=?,due_date=?,status=? WHERE id=? AND user_id=?");
$stmt->execute([$title,$description,$due_date,$status,$id,$_SESSION['user_id']]);
header('Location: dashboard.php'); exit();
}else $error="Vui lòng nhập đầy đủ thông tin và trạng thái hợp lệ!";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa công việc</title>
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
        <h2 class="text-center mb-4">✏️ Chỉnh sửa công việc</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">

            <div class="mb-3">
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title" 
                       class="form-control form-control-lg" 
                       value="<?= htmlspecialchars($task['title']) ?>" 
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" 
                          class="form-control" 
                          rows="3"><?= htmlspecialchars($task['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày hết hạn</label>
                <input type="date" name="due_date" 
                       class="form-control" 
                       value="<?= $task['due_date'] ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="pending"      <?= $task['status']=='pending'?'selected':'' ?>>Chờ xử lý</option>
                    <option value="in_progress" <?= $task['status']=='in_progress'?'selected':'' ?>>Đang thực hiện</option>
                    <option value="completed"   <?= $task['status']=='completed'?'selected':'' ?>>Hoàn thành</option>
                </select>
            </div>

            <button class="btn btn-primary btn-custom mb-2">💾 Cập nhật</button>
            <a href="dashboard.php" class="btn btn-secondary btn-custom">⬅ Quay lại</a>

        </form>
    </div>
</body>
</html>
