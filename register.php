<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $hashedPassword, $email]);
            header('Location: login.php');
            exit();
        } catch (PDOException $e) {
            $error = "Username hoặc Email đã tồn tại!";
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Đăng ký</h2>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Đăng ký</button>
        <a href="login.php" class="btn btn-link">Đăng nhập</a>
    </form>
</body>
</html>
