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
<html>
<head>
<title>Chỉnh sửa công việc</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h2>Chỉnh sửa công việc</h2>
<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="post">
<div class="mb-3">
<label>Tiêu đề</label>
<input type="text" name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
</div>
<div class="mb-3">
<label>Mô tả</label>
<textarea name="description" class="form-control"><?= htmlspecialchars($task['description']) ?></textarea>
</div>
<div class="mb-3">
<label>Ngày hết hạn</label>
<input type="date" name="due_date" class="form-control" value="<?= $task['due_date'] ?>">
</div>
<div class="mb-3">
<label>Trạng thái</label>
<select name="status" class="form-select">
<option value="pending" <?= $task['status']=='pending'?'selected':'' ?>>Chờ xử lý</option>
<option value="in_progress" <?= $task['status']=='in_progress'?'selected':'' ?>>Đang thực hiện</option>
<option value="completed" <?= $task['status']=='completed'?'selected':'' ?>>Hoàn thành</option>
</select>
</div>
<button class="btn btn-primary">Cập nhật</button>
<a href="dashboard.php" class="btn btn-secondary">Quay lại</a>
</form>
</body>
</html>
