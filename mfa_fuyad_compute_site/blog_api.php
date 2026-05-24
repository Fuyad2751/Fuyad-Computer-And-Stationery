<?php
header('Content-Type: application/json');
include 'config.php';

// GET: Load approved posts
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM blog_posts WHERE status = 'approved' ORDER BY created_at DESC");
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $posts]);
}

// POST: Submit new post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, author, status, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $data['title'], $data['content'], $data['author'], $data['status']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>