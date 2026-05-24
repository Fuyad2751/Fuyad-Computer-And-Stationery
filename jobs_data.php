<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

$host = 'sql302.infinityfree.com';
$user = 'if0_41570077';
$pass = 'Fuyad2026';
$db = 'if0_41570077_fuyad_computer';

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed'], JSON_UNESCAPED_UNICODE);
    exit;
}

$result = $conn->query("SELECT * FROM jobs ORDER BY id DESC");

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = [
        'id' => $row['id'],
        'title' => html_entity_decode($row['title'], ENT_QUOTES, 'UTF-8'),
        'company' => html_entity_decode($row['company'], ENT_QUOTES, 'UTF-8'),
        'location' => html_entity_decode($row['location'], ENT_QUOTES, 'UTF-8'),
        'deadline' => $row['deadline'],
        'application_start_date' => $row['application_start_date'],
        'application_end_date' => $row['application_end_date'],
        'category' => html_entity_decode($row['category'], ENT_QUOTES, 'UTF-8'),
        'salary' => html_entity_decode($row['salary'], ENT_QUOTES, 'UTF-8'),
        'positions' => $row['positions'],
        'job_type' => $row['job_type'],
        'circular_image' => $row['circular_image'],
        'apply_link' => $row['apply_link'],
        'description' => html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8'),
        'created_at' => $row['created_at']
    ];
}

echo json_encode(['success' => true, 'data' => $jobs], JSON_UNESCAPED_UNICODE);
$conn->close();
?>