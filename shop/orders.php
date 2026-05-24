<?php
session_start();
include '../config.php';

if (!isset($_SESSION['shop_user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['shop_user_id'];
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$user_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>আমার অর্ডার - Fuyad Stationery Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        
        .shop-header {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .logo-area { display: flex; align-items: center; gap: 15px; }
        .logo-area img { width: 45px; height: 45px; border-radius: 50%; }
        .logo-area h1 { font-size: 1.2rem; color: white; }
        .nav-links a {
            color: #e2e8f0;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 40px;
            margin: 0 3px;
        }
        .nav-links a:hover { background: #2563eb; }
        
        .container { max-width: 1000px; margin: 2rem auto; padding: 0 20px; }
        .title { font-size: 1.5rem; margin-bottom: 2rem; color: #1e293b; }
        
        .order-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 15px;
        }
        .order-no { font-weight: 700; color: #2563eb; }
        .order-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #cffafe; color: #0891b2; }
        .status-delivered { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        
        .order-items { margin: 15px 0; }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .order-total { 
            text-align: right; 
            margin-top: 15px; 
            padding-top: 10px; 
            border-top: 1px solid #e2e8f0;
        }
        .order-total strong { font-size: 1.1rem; color: #2563eb; }
        .order-total small { display: block; margin-top: 5px; color: #64748b; }
        
        .success-msg {
            background: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .empty-orders {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 20px;
        }
        .empty-orders i { font-size: 4rem; color: #94a3b8; margin-bottom: 1rem; }
        .shop-btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 30px;
            border-radius: 40px;
            text-decoration: none;
            margin-top: 20px;
        }
        
        .footer { background: #0f172a; color: white; text-align: center; padding: 2rem; margin-top: 3rem; }
        
        @media (max-width: 768px) {
            .shop-header { flex-direction: column; gap: 1rem; }
            .order-header { flex-direction: column; gap: 10px; }
            .order-item { flex-direction: column; gap: 5px; }
            .order-total { text-align: left; }
        }
    </style>
</head>
<body>

<header class="shop-header">
    <div class="logo-area">
        <img src="../Logo/logo.png" alt="Logo">
        <h1>Fuyad Stationery Shop</h1>
    </div>
    <div class="nav-links">
        <a href="index.php"><i class="fas fa-store"></i> স্টোর</a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i> কার্ট</a>
        <a href="orders.php"><i class="fas fa-box"></i> অর্ডার</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> লগআউট</a>
        <span style="color: white;">👋 <?php echo $_SESSION['shop_user_name']; ?></span>
    </div>
</header>

<div class="container">
    <h1 class="title"><i class="fas fa-box"></i> আমার অর্ডারসমূহ</h1>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="success-msg">
            <i class="fas fa-check-circle"></i> আপনার অর্ডার সফলভাবে সম্পন্ন হয়েছে! 
            অর্ডার নম্বর: <strong><?php echo $_SESSION['last_order_no'] ?? 'N/A'; ?></strong>
        </div>
    <?php endif; ?>
    
    <?php if($orders->num_rows > 0): ?>
        <?php while($order = $orders->fetch_assoc()): 
            $statusClass = '';
            $statusText = '';
            switch($order['status']) {
                case 'pending': $statusClass = 'status-pending'; $statusText = '⏳ অপেক্ষমাণ'; break;
                case 'processing': $statusClass = 'status-processing'; $statusText = '⚙️ প্রক্রিয়াধীন'; break;
                case 'shipped': $statusClass = 'status-shipped'; $statusText = '📦 পাঠানো হয়েছে'; break;
                case 'delivered': $statusClass = 'status-delivered'; $statusText = '✅ ডেলিভারি সম্পন্ন'; break;
                case 'cancelled': $statusClass = 'status-cancelled'; $statusText = '❌ বাতিল'; break;
                default: $statusClass = 'status-pending'; $statusText = 'অপেক্ষমাণ';
            }
            
            $paymentMethods = [
                'bkash' => 'bKash', 
                'nagad' => 'নগদ', 
                'rocket' => 'রকেট', 
                'cash_on_delivery' => 'নগদ (ডেলিভারির সময়)'
            ];
            $paymentText = $paymentMethods[$order['payment_method']] ?? $order['payment_method'];
        ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-no"><i class="fas fa-hashtag"></i> <?php echo $order['order_no']; ?></span><br>
                    <small><i class="far fa-calendar-alt"></i> <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></small>
                </div>
                <div>
                    <span class="order-status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                </div>
            </div>
            
            <div class="order-items">
                <?php 
                $items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = {$order['id']}");
                while($item = $items->fetch_assoc()):
                ?>
                <div class="order-item">
                    <span><strong><?php echo $item['name']; ?></strong> x <?php echo $item['quantity']; ?></span>
                    <span>৳ <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div class="order-total">
                <strong>মোট: ৳ <?php echo number_format($order['total_amount'], 2); ?></strong><br>
                <small><i class="fas fa-credit-card"></i> পেমেন্ট: <?php echo $paymentText; ?></small><br>
                <?php if($order['trx_id']): ?>
                    <small><i class="fas fa-hashtag"></i> ট্রানজেকশন ID: <?php echo $order['trx_id']; ?></small><br>
                <?php endif; ?>
                <small><i class="fas fa-map-marker-alt"></i> ডেলিভারি ঠিকানা: <?php echo $order['shipping_address']; ?></small><br>
                <small><i class="fas fa-phone"></i> মোবাইল: <?php echo $order['phone']; ?></small>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-orders">
            <i class="fas fa-box-open"></i>
            <h3>কোনো অর্ডার নেই</h3>
            <p>আপনি এখনও কোনো অর্ডার করেননি</p>
            <a href="index.php" class="shop-btn"><i class="fas fa-store"></i> কেনাকাটা শুরু করুন</a>
        </div>
    <?php endif; ?>
</div>

<footer class="footer">
    <p>&copy; 2025 Fuyad Computer & Stationery. All rights reserved.</p>
</footer>
</body>
</html>