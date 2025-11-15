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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            height: 100vh;
            background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-card {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        .register-card h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
            font-weight: bold;
        }
        .btn-primary {
            background: #6a11cb;
            border: none;
        }
        .btn-primary:hover {
            background: #2575fc;
        }
        .btn-link {
            color: #6a11cb;
        }
        .btn-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-card">
        <h2>Đăng ký</h2>

        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Nhập username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="example@gmail.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
            </div>

            <button class="btn btn-primary w-100">Đăng ký</button>

            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-link">Quay lại đăng nhập</a>
            </div>
        </form>
    </div>
</body>
</html>

