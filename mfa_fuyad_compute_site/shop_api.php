<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';

// ========== PRODUCT MANAGEMENT ==========
if ($action == 'get_products') {
    $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $products]);
}

if ($action == 'add_product') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO products (name, category, price, old_price, stock, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddiss", $data['name'], $data['category'], $data['price'], $data['old_price'], $data['stock'], $data['image'], $data['description']);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

if ($action == 'delete_product') {
    $data = json_decode(file_get_contents('php://input'), true);
    $conn->query("DELETE FROM products WHERE id=" . intval($data['id']));
    echo json_encode(['success' => true]);
}

// ========== ORDER MANAGEMENT ==========
if ($action == 'get_orders') {
    $result = $conn->query("SELECT o.*, u.name as user_name, u.email FROM orders o JOIN shop_users u ON o.user_id = u.id ORDER BY o.id DESC");
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $orders]);
}

// Get single order details
if ($action == 'get_order_details') {
    $order_id = intval($_GET['id'] ?? 0);
    $orderQuery = $conn->query("SELECT o.*, u.name as customer_name, u.email as customer_email FROM orders o JOIN shop_users u ON o.user_id = u.id WHERE o.id = $order_id");
    $order = $orderQuery->fetch_assoc();
    
    $itemsQuery = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id");
    $items = [];
    while ($item = $itemsQuery->fetch_assoc()) {
        $items[] = $item;
    }
    $order['items'] = $items;
    echo json_encode(['success' => true, 'order' => $order]);
}

// Update order status
if ($action == 'update_order_status') {
    $data = json_decode(file_get_contents('php://input'), true);
    $order_id = intval($data['order_id']);
    $status = $conn->real_escape_string($data['status']);
    
    if ($conn->query("UPDATE orders SET status='$status' WHERE id=$order_id")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

// ========== STATISTICS ==========
if ($action == 'get_stats') {
    $totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
    $totalOrders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
    $totalUsers = $conn->query("SELECT COUNT(*) as count FROM shop_users")->fetch_assoc()['count'];
    $totalRevenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status='delivered'")->fetch_assoc()['total'] ?? 0;
    $pendingOrders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status='pending'")->fetch_assoc()['count'];
    
    echo json_encode([
        'success' => true,
        'data' => [
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
            'total_users' => $totalUsers,
            'total_revenue' => $totalRevenue,
            'pending_orders' => $pendingOrders
        ]
    ]);
}
?>