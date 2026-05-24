<?php
session_start();
include '../config.php';

// Get cart count
$cartCount = 0;
if (isset($_SESSION['shop_user_id'])) {
    $uid = $_SESSION['shop_user_id'];
    $cartRes = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id=$uid");
    $cartCount = $cartRes->fetch_assoc()['total'] ?? 0;
}

// Get products
$category = $_GET['cat'] ?? '';
$sql = "SELECT * FROM products";
if ($category) $sql .= " WHERE category='$category'";
$sql .= " ORDER BY featured DESC, created_at DESC";
$products = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuyad Computer and Stationery</title>
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
        .cart-icon { position: relative; }
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 50%;
        }
        
        .hero { background: linear-gradient(135deg, #2563eb, #1e40af); color: white; text-align: center; padding: 3rem; }
        .hero h1 { font-size: 2rem; margin-bottom: 0.5rem; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .categories { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 2rem; }
        .cat-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .cat-btn.active { background: #2563eb; color: white; }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.5rem;
        }
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .product-img { width: 100%; height: 200px; object-fit: cover; background: #f1f5f9; }
        .product-info { padding: 1rem; }
        .product-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .price { font-size: 1.2rem; font-weight: 700; color: #2563eb; }
        .old-price { font-size: 0.8rem; color: #94a3b8; text-decoration: line-through; margin-left: 8px; }
        .add-to-cart {
            width: 100%;
            padding: 8px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .footer { background: #0f172a; color: white; text-align: center; padding: 2rem; margin-top: 2rem; }
        .chat-btn {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 55px;
            height: 55px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 99;
        }
        .chat-window {
            position: fixed;
            bottom: 150px;
            right: 20px;
            width: 350px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            display: none;
            z-index: 100;
        }
        .chat-header {
            background: #2563eb;
            color: white;
            padding: 15px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
        }
        .chat-messages { height: 300px; overflow-y: auto; padding: 15px; }
        .chat-input-area { display: flex; padding: 10px; border-top: 1px solid #e2e8f0; }
        .chat-input-area input { flex: 1; padding: 10px; border: 1px solid #e2e8f0; border-radius: 30px; }
        .chat-input-area button { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 30px; margin-left: 10px; }
        .message { margin-bottom: 10px; padding: 8px 12px; border-radius: 15px; max-width: 80%; }
        .user-message { background: #2563eb; color: white; margin-left: auto; text-align: right; }
        .admin-message { background: #f1f5f9; color: #1e293b; }
        
        @media (max-width: 768px) {
            .shop-header { flex-direction: column; gap: 1rem; }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .chat-window { width: 300px; right: 10px; bottom: 130px; }
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
        <a href="cart.php" class="cart-icon"><i class="fas fa-shopping-cart"></i> কার্ট <span class="cart-count"><?php echo $cartCount; ?></span></a>
        <?php if(isset($_SESSION['shop_user_id'])): ?>
            <a href="orders.php"><i class="fas fa-box"></i> অর্ডার</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> লগআউট</a>
            <span style="color: white; margin-left: 10px;">👋 <?php echo $_SESSION['shop_user_name']; ?></span>
        <?php else: ?>
            <a href="login.php"><i class="fas fa-user"></i> লগইন</a>
            <a href="register.php"><i class="fas fa-user-plus"></i> রেজিস্ট্রেশন</a>
        <?php endif; ?>
        <a href="../index.html"><i class="fas fa-home"></i> মূল সাইট</a>
    </div>
</header>

<section class="hero">
     <h1><i>ফুয়াদ কম্পিউটার এন্ড স্টেশনারী</i> </h1>
    <h2><i class="fas fa-pen-fancy"></i> স্বাগতম আমাদের স্টেশনারি শপে</h2>
    <p>গুণগত মানের স্টেশনারি পণ্য সাশ্রয়ী মূল্যে</p>
</section>

<div class="container">
    <div class="categories">
        <button class="cat-btn <?php echo !$category ? 'active' : ''; ?>" onclick="filterCategory('')">সবগুলো</button>
        <button class="cat-btn <?php echo $category == 'কলম' ? 'active' : ''; ?>" onclick="filterCategory('কলম')">🖊️ কলম</button>
        <button class="cat-btn <?php echo $category == 'খাতা' ? 'active' : ''; ?>" onclick="filterCategory('খাতা')">📓 খাতা</button>
        <button class="cat-btn <?php echo $category == 'স্টেশনারী' ? 'active' : ''; ?>" onclick="filterCategory('স্টেশনারী')">📌 স্টেশনারী</button>
        <button class="cat-btn <?php echo $category == 'এক্সেসরিজ' ? 'active' : ''; ?>" onclick="filterCategory('এক্সেসরিজ')">🎒 এক্সেসরিজ</button>
    </div>
    
    <div class="products-grid">
        <?php while($product = $products->fetch_assoc()): ?>
        <div class="product-card">
            <img src="<?php echo $product['image']; ?>" class="product-img" onerror="this.src='https://placehold.co/400x400?text=Product'">
            <div class="product-info">
                <div class="product-title"><?php echo $product['name']; ?></div>
                <div class="price">৳ <?php echo number_format($product['price'], 2); ?>
                    <?php if($product['old_price']): ?>
                        <span class="old-price">৳ <?php echo number_format($product['old_price'], 2); ?></span>
                    <?php endif; ?>
                </div>
                <button class="add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>)">
                    <i class="fas fa-cart-plus"></i> কার্টে যোগ করুন
                </button>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Live Chat -->
<div class="chat-btn" onclick="toggleChat()">
    <i class="fas fa-comment-dots fa-2x" style="color: white;"></i>
</div>
<div class="chat-window" id="chatWindow">
    <div class="chat-header">
        <span><i class="fas fa-headset"></i> কাস্টমার সাপোর্ট</span>
        <i class="fas fa-times" onclick="toggleChat()" style="cursor: pointer;"></i>
    </div>
    <div class="chat-messages" id="chatMessages"></div>
    <div class="chat-input-area">
        <input type="text" id="chatInput" placeholder="আপনার বার্তা লিখুন...">
        <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2025 Fuyad Computer & Stationery. All rights reserved.</p>
</footer>

<script>
    let currentUser = <?php echo isset($_SESSION['shop_user_id']) ? $_SESSION['shop_user_id'] : 'null'; ?>;
    let userName = '<?php echo $_SESSION['shop_user_name'] ?? "Guest"; ?>';
    
    function filterCategory(cat) {
        if (cat) window.location.href = `?cat=${encodeURIComponent(cat)}`;
        else window.location.href = 'index.php';
    }
    
    function addToCart(productId) {
        <?php if(!isset($_SESSION['shop_user_id'])): ?>
            alert('দয়া করে লগইন করুন');
            window.location.href = 'login.php';
            return;
        <?php endif; ?>
        
        fetch('shop_api.php?action=add_to_cart', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✅ পণ্য কার্টে যোগ হয়েছে');
                location.reload();
            } else {
                alert('❌ ' + data.error);
            }
        });
    }
    
    // Live Chat Functions
    let chatInterval;
    
    function toggleChat() {
        const win = document.getElementById('chatWindow');
        if (win.style.display === 'none' || !win.style.display) {
            win.style.display = 'block';
            loadMessages();
            chatInterval = setInterval(loadMessages, 5000);
        } else {
            win.style.display = 'none';
            clearInterval(chatInterval);
        }
    }
    
    function loadMessages() {
        fetch('shop_api.php?action=get_messages')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const container = document.getElementById('chatMessages');
                    container.innerHTML = data.messages.map(msg => `
                        <div class="message ${msg.user_id == currentUser ? 'user-message' : 'admin-message'}">
                            <strong>${msg.user_name}:</strong> ${escapeHtml(msg.message)}
                            <div style="font-size: 10px; opacity: 0.7;">${msg.time}</div>
                        </div>
                    `).join('');
                    container.scrollTop = container.scrollHeight;
                }
            });
    }
    
    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;
        
        fetch('shop_api.php?action=send_message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message, user_name: userName })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                loadMessages();
            }
        });
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
</body>
</html>