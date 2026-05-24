<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include 'config.php';

// GET: Load all reviews
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT id, name, rating, service, review, date FROM reviews ORDER BY id DESC");
    $reviews = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'rating' => (int)$row['rating'],
                'service' => $row['service'],
                'review' => $row['review'],
                'date' => $row['date']
            ];
        }
        echo json_encode(['success' => true, 'data' => $reviews]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

// POST: Add new review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $name = $conn->real_escape_string($data['name']);
    $rating = (int)$data['rating'];
    $service = $conn->real_escape_string($data['service']);
    $review = $conn->real_escape_string($data['review']);
    
    // রেটিং 1-5 এর মধ্যে রাখুন
    if ($rating < 1) $rating = 1;
    if ($rating > 5) $rating = 5;
    
    $stmt = $conn->prepare("INSERT INTO reviews (name, rating, service, review, date) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("siss", $name, $rating, $service, $review);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
}
$conn->close();
?>