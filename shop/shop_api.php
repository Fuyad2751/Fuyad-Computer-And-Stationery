<?php
session_start();
include '../config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Add to cart
if ($action == 'add_to_cart') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['shop_user_id'] ?? 0;
    $product_id = $data['product_id'] ?? 0;
    
    if (!$user_id) {
        echo json_encode(['success' => false, 'error' => 'Please login']);
        exit;
    }
    
    $check = $conn->query("SELECT id FROM cart WHERE user_id=$user_id AND product_id=$product_id");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id=$user_id AND product_id=$product_id");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id) VALUES ($user_id, $product_id)");
    }
    
    echo json_encode(['success' => true]);
}

// Get messages
elseif ($action == 'get_messages') {
    $result = $conn->query("SELECT * FROM chat_messages ORDER BY id DESC LIMIT 50");
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode(['success' => true, 'messages' => array_reverse($messages)]);
}

// Send message
elseif ($action == 'send_message') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['shop_user_id'] ?? 0;
    $user_name = $data['user_name'] ?? 'Guest';
    $message = $conn->real_escape_string($data['message']);
    
    $conn->query("INSERT INTO chat_messages (user_id, user_name, message, is_admin_reply) VALUES ($user_id, '$user_name', '$message', 0)");
    echo json_encode(['success' => true]);
}

// Get cart items
elseif ($action == 'get_cart') {
    $user_id = $_SESSION['shop_user_id'] ?? 0;
    $result = $conn->query("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id=$user_id");
    $items = [];
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $subtotal = $row['price'] * $row['quantity'];
        $total += $subtotal;
        $items[] = $row;
    }
    echo json_encode(['success' => true, 'items' => $items, 'total' => $total]);
}

// Update cart quantity
elseif ($action == 'update_cart') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cart_id = $data['cart_id'];
    $quantity = $data['quantity'];
    $conn->query("UPDATE cart SET quantity=$quantity WHERE id=$cart_id");
    echo json_encode(['success' => true]);
}

// Remove from cart
elseif ($action == 'remove_from_cart') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cart_id = $data['cart_id'];
    $conn->query("DELETE FROM cart WHERE id=$cart_id");
    echo json_encode(['success' => true]);
}

// Place order
elseif ($action == 'place_order') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['shop_user_id'] ?? 0;
    $total = $data['total'];
    $address = $conn->real_escape_string($data['address']);
    $phone = $data['phone'];
    $order_no = 'ORD' . date('Ymd') . rand(1000, 9999);
    
    $conn->query("INSERT INTO orders (order_no, user_id, total_amount, shipping_address, phone) VALUES ('$order_no', $user_id, $total, '$address', '$phone')");
    $order_id = $conn->insert_id;
    
    $cart = $conn->query("SELECT * FROM cart WHERE user_id=$user_id");
    while ($item = $cart->fetch_assoc()) {
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, (SELECT price FROM products WHERE id={$item['product_id']}))");
    }
    
    $conn->query("DELETE FROM cart WHERE user_id=$user_id");
    echo json_encode(['success' => true, 'order_no' => $order_no]);
}
?>