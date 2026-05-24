<?php
session_start();
include '../config.php';

if (isset($_SESSION['shop_user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    
    $result = $conn->query("SELECT * FROM shop_users WHERE email='$email' AND password='$password'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['shop_user_id'] = $user['id'];
        $_SESSION['shop_user_name'] = $user['name'];
        $_SESSION['shop_user_email'] = $user['email'];
        $_SESSION['shop_user_role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Login - Fuyad Stationery Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .logo { text-align: center; margin-bottom: 30px; }
        .logo img { width: 70px; border-radius: 50%; }
        .logo h2 { margin-top: 10px; color: #1e293b; }
        .form-group { margin-bottom: 20px; }
        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            border: none;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 10px; margin-bottom: 20px; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="logo">
        <img src="../Logo/logo.png" alt="Logo">
        <h2>Fuyad Stationery Shop</h2>
        <p style="color: #64748b;">লগইন করুন</p>
    </div>
    <?php if($error): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <input type="email" name="email" placeholder="ইমেইল" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="পাসওয়ার্ড" required>
        </div>
        <button type="submit" class="btn"><i class="fas fa-sign-in-alt"></i> লগইন</button>
    </form>
    <div class="links">
        <p>নতুন ইউজার? <a href="register.php">একাউন্ট খুলুন</a></p>
        <p><a href="../index.html">← হোমপেজে ফিরুন</a></p>
    </div>
</div>
</body>
</html>