<?php
header('Content-Type: application/json');
include 'config.php';

$result = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
$gallery = [];

while ($row = $result->fetch_assoc()) {
    $gallery[] = $row;
}

echo json_encode(['success' => true, 'data' => $gallery]);
?>