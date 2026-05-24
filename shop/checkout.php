<?php
session_start();
include '../config.php';

if (!isset($_SESSION['shop_user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['shop_user_id'];
$user = $conn->query("SELECT * FROM shop_users WHERE id=$user_id")->fetch_assoc();

// Get cart total
$cartTotal = $conn->query("SELECT SUM(p.price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id=$user_id")->fetch_assoc()['total'];

if ($cartTotal == 0) {
    header('Location: cart.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];
    $trx_id = $_POST['trx_id'] ?? '';
    
    if (empty($address) || empty($phone)) {
        $error = 'দয়া করে ঠিকানা এবং মোবাইল নম্বর দিন';
    } elseif (($payment_method == 'bkash' || $payment_method == 'nagad' || $payment_method == 'rocket') && empty($trx_id)) {
        $error = 'দয়া করে ট্রানজেকশন আইডি দিন';
    } else {
        $order_no = 'ORD' . date('Ymd') . rand(1000, 9999);
        $payment_status = ($payment_method == 'cash_on_delivery') ? 'pending' : 'paid';
        
        $conn->query("INSERT INTO orders (order_no, user_id, total_amount, shipping_address, phone, payment_method, payment_status, trx_id) 
                      VALUES ('$order_no', $user_id, $cartTotal, '$address', '$phone', '$payment_method', '$payment_status', '$trx_id')");
        $order_id = $conn->insert_id;
        
        $cart = $conn->query("SELECT * FROM cart WHERE user_id=$user_id");
        while ($item = $cart->fetch_assoc()) {
            $product = $conn->query("SELECT price FROM products WHERE id={$item['product_id']}")->fetch_assoc();
            $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                          VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$product['price']})");
        }
        
        $conn->query("DELETE FROM cart WHERE user_id=$user_id");
        
        $_SESSION['last_order_no'] = $order_no;
        header('Location: orders.php?success=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>চেকআউট - Fuyad Stationery Shop</title>
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
        .checkout-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .title { font-size: 1.5rem; margin-bottom: 1.5rem; color: #1e293b; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
        }
        
        /* Payment Methods */
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .payment-option {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 15px;
            cursor: pointer;
            text-align: center;
            transition: 0.3s;
        }
        .payment-option:hover { border-color: #2563eb; background: #f8fafc; }
        .payment-option.selected { border-color: #10b981; background: #d1fae5; }
        .payment-option i { font-size: 2rem; margin-bottom: 8px; display: block; }
        .payment-option .bkash { color: #e2136e; }
        .payment-option .nagad { color: #f5a623; }
        .payment-option .rocket { color: #6c5ce7; }
        .payment-option .cod { color: #10b981; }
        
        .trx-field { display: none; margin-top: 15px; }
        .trx-field.show { display: block; }
        
        .order-summary {
            background: #f1f5f9;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
        }
        .total { font-size: 1.3rem; font-weight: 700; color: #2563eb; }
        .place-order-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 10px; margin-bottom: 20px; }
        
        /* Payment Info Modal */
        .payment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .payment-modal-content {
            background: white;
            border-radius: 24px;
            padding: 25px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        .payment-number {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 12px;
            font-size: 1.3rem;
            font-weight: 700;
            margin: 15px 0;
        }
        .copy-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
        }
        
        .footer { background: #0f172a; color: white; text-align: center; padding: 2rem; margin-top: 3rem; }
        
        @media (max-width: 768px) {
            .shop-header { flex-direction: column; gap: 1rem; }
            .checkout-card { padding: 20px; }
            .payment-methods { grid-template-columns: repeat(2, 1fr); }
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
    <div class="checkout-card">
        <h1 class="title"><i class="fas fa-credit-card"></i> চেকআউট</h1>
        
        <?php if($error): ?>
            <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" id="checkoutForm">
            <div class="form-group">
                <label>নাম</label>
                <input type="text" value="<?php echo $user['name']; ?>" readonly style="background:#f1f5f9;">
            </div>
            <div class="form-group">
                <label>ইমেইল</label>
                <input type="email" value="<?php echo $user['email']; ?>" readonly style="background:#f1f5f9;">
            </div>
            <div class="form-group">
                <label>মোবাইল নম্বর *</label>
                <input type="tel" name="phone" value="<?php echo $user['phone']; ?>" required placeholder="01XXXXXXXXX">
            </div>
            <div class="form-group">
                <label>ডেলিভারি ঠিকানা *</label>
                <textarea name="address" rows="3" required placeholder="বিস্তারিত ঠিকানা দিন"><?php echo $user['address']; ?></textarea>
            </div>
            
            <label style="font-weight: 500; margin-bottom: 10px; display: block;">পেমেন্ট পদ্ধতি *</label>
            <div class="payment-methods">
                <div class="payment-option" data-method="bkash" onclick="selectPayment('bkash')">
                    <i class="fab fa-bkash bkash"></i>
                    <strong>bKash</strong>
                </div>
                <div class="payment-option" data-method="nagad" onclick="selectPayment('nagad')">
                    <i class="fas fa-mobile-alt nagad"></i>
                    <strong>নগদ</strong>
                </div>
                <div class="payment-option" data-method="rocket" onclick="selectPayment('rocket')">
                    <i class="fas fa-rocket rocket"></i>
                    <strong>রকেট</strong>
                </div>
                <div class="payment-option" data-method="cash_on_delivery" onclick="selectPayment('cash_on_delivery')">
                    <i class="fas fa-hand-holding-usd cod"></i>
                    <strong>নগদ (ডেলিভারির সময়)</strong>
                </div>
            </div>
            <input type="hidden" name="payment_method" id="selectedPayment" value="">
            
            <div id="trxField" class="trx-field">
                <div class="form-group">
                    <label>ট্রানজেকশন আইডি (Transaction ID) *</label>
                    <input type="text" name="trx_id" placeholder="যেমন: BKASH123456789">
                    <small style="color:#64748b; display: block; margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> পেমেন্ট করার পর ট্রানজেকশন আইডি দিন
                    </small>
                </div>
                <button type="button" class="copy-btn" onclick="showPaymentInfo()">
                    <i class="fas fa-info-circle"></i> পেমেন্ট নম্বর দেখুন
                </button>
            </div>
            
            <div class="order-summary">
                <h3>অর্ডার সামারি</h3>
                <p>মোট পণ্যের মূল্য: ৳ <?php echo number_format($cartTotal, 2); ?></p>
                <p>ডেলিভারি চার্জ: <strong>বিনামূল্যে</strong></p>
                <hr style="margin: 10px 0;">
                <p class="total">মোট পরিশোধযোগ্য: ৳ <?php echo number_format($cartTotal, 2); ?></p>
            </div>
            
            <button type="submit" class="place-order-btn"><i class="fas fa-check-circle"></i> অর্ডার কনফার্ম করুন</button>
        </form>
    </div>
</div>

<!-- Payment Info Modal -->
<div id="paymentModal" class="payment-modal">
    <div class="payment-modal-content">
        <i class="fas fa-mobile-alt" style="font-size: 3rem; color: #2563eb;"></i>
        <h3 style="margin: 10px 0;">পেমেন্ট করুন</h3>
        <p>নিচের নম্বরে টাকা পাঠিয়ে ট্রানজেকশন আইডি দিন</p>
        <div class="payment-number" id="paymentNumberDisplay"></div>
        <button class="copy-btn" onclick="copyPaymentNumber()"><i class="fas fa-copy"></i> নম্বর কপি করুন</button>
        <button class="copy-btn" style="background: #64748b; margin-top: 10px;" onclick="closePaymentModal()">Close</button>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2025 Fuyad Computer & Stationery. All rights reserved.</p>
</footer>

<script>
    let selectedMethod = '';
    
    // Payment numbers
    const paymentNumbers = {
        bkash: '017840103056',
        nagad: '017840103056',
        rocket: '017840103056'
    };
    
    function selectPayment(method) {
        selectedMethod = method;
        document.getElementById('selectedPayment').value = method;
        
        // Remove selected class from all
        document.querySelectorAll('.payment-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        document.querySelector(`.payment-option[data-method="${method}"]`).classList.add('selected');
        
        // Show/hide TRX field for mobile payments
        const trxField = document.getElementById('trxField');
        if (method === 'cash_on_delivery') {
            trxField.classList.remove('show');
        } else {
            trxField.classList.add('show');
        }
    }
    
    function showPaymentInfo() {
        if (!selectedMethod || selectedMethod === 'cash_on_delivery') {
            alert('দয়া করে একটি মোবাইল পেমেন্ট পদ্ধতি নির্বাচন করুন');
            return;
        }
        
        const number = paymentNumbers[selectedMethod];
        let methodName = '';
        if (selectedMethod === 'bkash') methodName = 'bKash';
        else if (selectedMethod === 'nagad') methodName = 'নগদ';
        else if (selectedMethod === 'rocket') methodName = 'রকেট';
        
        document.getElementById('paymentNumberDisplay').innerHTML = `
            <strong>${methodName} নম্বর:</strong><br>
            <span style="font-size: 1.5rem;">${number}</span>
        `;
        document.getElementById('paymentModal').style.display = 'flex';
    }
    
    function copyPaymentNumber() {
        const number = paymentNumbers[selectedMethod];
        navigator.clipboard.writeText(number);
        alert('নম্বর কপি করা হয়েছে!');
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').style.display = 'none';
    }
    
    // Form validation before submit
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const paymentMethod = document.getElementById('selectedPayment').value;
        if (!paymentMethod) {
            e.preventDefault();
            alert('দয়া করে একটি পেমেন্ট পদ্ধতি নির্বাচন করুন');
            return;
        }
        
        if (paymentMethod !== 'cash_on_delivery') {
            const trxId = document.querySelector('input[name="trx_id"]').value;
            if (!trxId) {
                e.preventDefault();
                alert('দয়া করে ট্রানজেকশন আইডি দিন');
                return;
            }
        }
    });
    
    // Close modal on outside click
    window.onclick = function(event) {
        const modal = document.getElementById('paymentModal');
        if (event.target === modal) closePaymentModal();
    }
</script>
</body>
</html>