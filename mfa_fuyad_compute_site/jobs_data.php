<?php
header('Content-Type: application/json');
error_reporting(0);

// Database connection
$host = 'sql302.infinityfree.com';
$user = 'if0_41570077';
$pass = 'Fuyad2026';
$db = 'if0_41570077_fuyad_computer';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$sql = "SELECT * FROM jobs ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['success' => false, 'error' => 'Query failed: ' . $conn->error]);
    exit;
}

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'company' => $row['company'],
        'location' => $row['location'],
        'deadline' => $row['deadline'],
        'application_start_date' => $row['application_start_date'],
        'application_end_date' => $row['application_end_date'],
        'category' => $row['category'],
        'salary' => $row['salary'],
        'positions' => $row['positions'],
        'job_type' => $row['job_type'],
        'circular_image' => $row['circular_image'],
        'apply_link' => $row['apply_link'],
        'description' => $row['description'],
        'created_at' => $row['created_at']
    ];
}

echo json_encode(['success' => true, 'data' => $jobs]);
$conn->close();
?>