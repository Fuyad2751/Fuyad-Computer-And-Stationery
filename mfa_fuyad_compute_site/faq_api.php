<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
include 'config.php';

// GET: Load all FAQs
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM faqs ORDER BY category, display_order");
    $faqs = [];
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $faqs]);
}

// POST: Add or Update FAQ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['id']) && $data['id'] > 0) {
        // Update
        $stmt = $conn->prepare("UPDATE faqs SET question=?, answer=?, category=?, display_order=? WHERE id=?");
        $stmt->bind_param("sssii", $data['question'], $data['answer'], $data['category'], $data['display_order'], $data['id']);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO faqs (question, answer, category, display_order) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $data['question'], $data['answer'], $data['category'], $data['display_order']);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

// DELETE: Remove FAQ
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'] ?? 0;
    $conn->query("DELETE FROM faqs WHERE id=$id");
    echo json_encode(['success' => true]);
}
?>