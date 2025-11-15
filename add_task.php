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
<html>
<head>
<title>Thêm công việc</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h2>Thêm công việc mới</h2>
<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="post">
<div class="mb-3">
<label>Tiêu đề</label>
<input type="text" name="title" class="form-control" required>
</div>
<div class="mb-3">
<label>Mô tả</label>
<textarea name="description" class="form-control"></textarea>
</div>
<div class="mb-3">
<label>Ngày hết hạn</label>
<input type="date" name="due_date" class="form-control">
</div>
<button class="btn btn-success">Thêm</button>
<a href="dashboard.php" class="btn btn-secondary">Quay lại</a>
</form>
</body>
</html>
