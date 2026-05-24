<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include 'config.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is received
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit;
}

// Validate required fields
if (empty($data['name']) || empty($data['phone']) || empty($data['service']) || empty($data['message'])) {
    echo json_encode(['success' => false, 'error' => 'Please fill all required fields']);
    exit;
}

// Prepare and execute query
$stmt = $conn->prepare("INSERT INTO service_requests (name, phone, email, service, priority, preferred_date, message, request_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssssss", 
    $data['name'], 
    $data['phone'], 
    $data['email'], 
    $data['service'], 
    $data['priority'], 
    $data['preferredDate'], 
    $data['message']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Request saved successfully']);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$stmt->close();
$conn->close();
?>