<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $check = $conn->query("SELECT id FROM shop_users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = 'এই ইমেইল уже রেজিস্টার করা আছে';
    } else {
        $conn->query("INSERT INTO shop_users (name, email, password, phone, address) VALUES ('$name', '$email', '$password', '$phone', '$address')");
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Register - Fuyad Stationery Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .register-container {
            background: white;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .logo { text-align: center; margin-bottom: 30px; }
        .logo img { width: 60px; border-radius: 50%; }
        .form-group { margin-bottom: 15px; }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
        }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 10px; margin-bottom: 20px; }
        .links { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
<div class="register-container">
    <div class="logo">
        <img src="../Logo/logo.png" alt="Logo">
        <h2>নতুন একাউন্ট খুলুন</h2>
    </div>
    <?php if(isset($error)): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group"><input type="text" name="name" placeholder="আপনার নাম" required></div>
        <div class="form-group"><input type="email" name="email" placeholder="ইমেইল" required></div>
        <div class="form-group"><input type="password" name="password" placeholder="পাসওয়ার্ড" required></div>
        <div class="form-group"><input type="tel" name="phone" placeholder="মোবাইল নম্বর"></div>
        <div class="form-group"><textarea name="address" rows="2" placeholder="ঠিকানা"></textarea></div>
        <button type="submit" class="btn"><i class="fas fa-user-plus"></i> রেজিস্ট্রেশন</button>
    </form>
    <div class="links">
        <p>ইতিমধ্যে একাউন্ট আছে? <a href="login.php">লগইন করুন</a></p>
    </div>
</div>
</body>
</html>