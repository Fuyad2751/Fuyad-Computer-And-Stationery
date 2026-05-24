<?php
session_start();
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
include 'config.php';

$action = $_GET['action'] ?? '';

// Upload file for job
if ($action == 'upload_job_file') {
    if (!isset($_FILES['job_file']) || $_FILES['job_file']['error'] != 0) {
        echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
        exit;
    }
    
    $job_id = intval($_POST['job_id']);
    $file = $_FILES['job_file'];
    $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
    $file_type = mime_content_type($file['tmp_name']);
    
    // Create upload directory if not exists
    $upload_dir = '../uploads/job_circulars/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $target_path = $upload_dir . $file_name;
    $db_path = 'uploads/job_circulars/' . $file_name;
    
    // Check file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'application/pdf'];
    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(['success' => false, 'error' => 'Only JPG, PNG, GIF, and PDF files are allowed']);
        exit;
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $file_type_db = (strpos($file_type, 'pdf') !== false) ? 'pdf' : 'image';
        $conn->query("UPDATE jobs SET circular_file = '$db_path', circular_file_type = '$file_type_db' WHERE id = $job_id");
        echo json_encode(['success' => true, 'file_path' => $db_path, 'file_type' => $file_type_db]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
    }
    exit;
}

// Delete job file
if ($action == 'delete_job_file') {
    $job_id = intval($_POST['job_id']);
    $result = $conn->query("SELECT circular_file FROM jobs WHERE id = $job_id");
    $job = $result->fetch_assoc();
    
    if ($job && $job['circular_file']) {
        $file_path = '../' . $job['circular_file'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $conn->query("UPDATE jobs SET circular_file = NULL, circular_file_type = NULL WHERE id = $job_id");
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No file found']);
    }
    exit;
}
?>