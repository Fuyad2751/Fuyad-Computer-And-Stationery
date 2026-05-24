<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['shop_user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['shop_user_id'];

// Get cart items
$cartItems = $conn->query("
    SELECT c.*, p.name, p.price, p.image, p.stock 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = $user_id
");

$total = 0;
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শপিং কার্ট - Fuyad Stationery Shop</title>
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
            position: sticky;
            top: 0;
            z-index: 100;
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
        
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 20px; }
        .cart-title { font-size: 1.8rem; margin-bottom: 2rem; color: #1e293b; }
        
        .cart-table {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px 20px; background: #f1f5f9; font-weight: 600; }
        td { padding: 15px 20px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; }
        .quantity-input {
            width: 60px;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
        }
        .remove-btn {
            background: #fee2e2;
            color: #ef4444;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
        }
        .cart-summary {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-top: 20px;
            text-align: right;
        }
        .total { font-size: 1.5rem; font-weight: 700; color: #2563eb; }
        .checkout-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
        }
        .empty-cart {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 20px;
        }
        .empty-cart i { font-size: 4rem; color: #94a3b8; margin-bottom: 1rem; }
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
            th, td { padding: 10px; }
            .product-img { width: 40px; height: 40px; }
            .cart-title { font-size: 1.4rem; }
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
        <span style="color: white; margin-left: 10px;">👋 <?php echo $_SESSION['shop_user_name']; ?></span>
    </div>
</header>

<div class="container">
    <h1 class="cart-title"><i class="fas fa-shopping-cart"></i> আপনার শপিং কার্ট</h1>
    
    <?php if($cartItems->num_rows > 0): ?>
    <div class="cart-table">
        <table>
            <thead>
                <tr><th>পণ্য</th><th>নাম</th><th>মূল্য</th><th>পরিমাণ</th><th>মোট</th><th></th></tr>
            </thead>
            <tbody id="cartBody">
                <?php while($item = $cartItems->fetch_assoc()): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr id="cart-row-<?php echo $item['id']; ?>">
                    <td><img src="<?php echo $item['image']; ?>" class="product-img" onerror="this.src='https://placehold.co/60x60'"></td>
                    <td><?php echo $item['name']; ?></td>
                    <td>৳ <?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <input type="number" class="quantity-input" value="<?php echo $item['quantity']; ?>" 
                               min="1" max="<?php echo $item['stock']; ?>" 
                               onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                    </td>
                    <td class="subtotal-<?php echo $item['id']; ?>">৳ <?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <button class="remove-btn" onclick="removeFromCart(<?php echo $item['id']; ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: 600;">
                    <td colspan="4" style="text-align: right;">সর্বমোট:</td>
                    <td colspan="2" class="total-amount">৳ <?php echo number_format($total, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="cart-summary">
        <p>মোট পরিশোধযোগ্য: <span class="total">৳ <?php echo number_format($total, 2); ?></span></p>
        <button class="checkout-btn" onclick="proceedToCheckout()"><i class="fas fa-credit-card"></i> চেকআউট করুন</button>
    </div>
    
    <?php else: ?>
    <div class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <h3>আপনার কার্ট খালি</h3>
        <p>দয়া করে কিছু পণ্য যোগ করুন</p>
        <a href="index.php" class="shop-btn"><i class="fas fa-store"></i> কেনাকাটা শুরু করুন</a>
    </div>
    <?php endif; ?>
</div>

<footer class="footer">
    <p>&copy; 2025 Fuyad Computer & Stationery. All rights reserved.</p>
</footer>

<script>
    let currentTotal = <?php echo $total; ?>;
    
    function updateQuantity(cartId, quantity) {
        if (quantity < 1) quantity = 1;
        
        fetch('shop_api.php?action=update_cart', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart_id: cartId, quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
    
    function removeFromCart(cartId) {
        if (confirm('পণ্যটি কার্ট থেকে সরাতে চান?')) {
            fetch('shop_api.php?action=remove_from_cart', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_id: cartId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
    
    function proceedToCheckout() {
        window.location.href = 'checkout.php';
    }
</script>
</body>
</html>