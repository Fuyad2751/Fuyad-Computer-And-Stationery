<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .login-box { max-width: 400px; margin: 100px auto; padding: 30px; background: var(--bg-card); border-radius: 20px; }
        .login-box input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 10px; border: 1px solid var(--border-color); }
        .login-box button { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 40px; cursor: pointer; }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include 'config.php';
        $user = $_POST['username'];
        $pass = md5($_POST['password']);
        $res = $conn->query("SELECT * FROM admin WHERE username='$user' AND password='$pass'");
        if ($res->num_rows) {
            $_SESSION['admin'] = $user;
            header('Location: admin_dashboard.php');
        } else echo "<p style='color:red; text-align:center; margin-top:10px;'>Invalid login</p>";
    }
    ?>
</div>
</body>
</html>