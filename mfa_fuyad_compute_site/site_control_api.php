<?php
session_start();
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
error_reporting(0);
include 'config.php';

$action = $_GET['action'] ?? '';

// ========== SITE SETTINGS ==========
if ($action == 'get_site_settings') {
    $result = $conn->query("SELECT setting_key, setting_value FROM site_settings");
    $settings = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    echo json_encode(['success' => true, 'data' => $settings]);
    exit;
}

if ($action == 'update_site_settings') {
    $data = json_decode(file_get_contents('php://input'), true);
    foreach ($data as $key => $value) {
        $value = $conn->real_escape_string($value);
        $conn->query("INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$value') 
                      ON DUPLICATE KEY UPDATE setting_value = '$value'");
    }
    echo json_encode(['success' => true]);
    exit;
}

// ========== SERVICES MANAGEMENT ==========
if ($action == 'get_services') {
    $result = $conn->query("SELECT * FROM services_settings ORDER BY display_order ASC");
    $services = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }
    echo json_encode(['success' => true, 'data' => $services]);
    exit;
}

if ($action == 'add_service') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO services_settings (service_title, service_url, service_icon, display_order) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $data['title'], $data['url'], $data['icon'], $data['order']);
    $stmt->execute();
    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    exit;
}

if ($action == 'delete_service') {
    $data = json_decode(file_get_contents('php://input'), true);
    $conn->query("DELETE FROM services_settings WHERE id=" . intval($data['id']));
    echo json_encode(['success' => true]);
    exit;
}

// ========== REVIEW SERVICES MANAGEMENT ==========
if ($action == 'get_review_services') {
    $result = $conn->query("SELECT * FROM review_services ORDER BY display_order ASC");
    $services = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }
    echo json_encode(['success' => true, 'data' => $services]);
    exit;
}

if ($action == 'add_review_service') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO review_services (service_name, display_order) VALUES (?, ?)");
    $stmt->bind_param("si", $data['name'], $data['order']);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

if ($action == 'delete_review_service') {
    $data = json_decode(file_get_contents('php://input'), true);
    $conn->query("DELETE FROM review_services WHERE id=" . intval($data['id']));
    echo json_encode(['success' => true]);
    exit;
}

// ========== THEME SETTINGS ==========
if ($action == 'get_theme_settings') {
    $result = $conn->query("SELECT setting_key, setting_value FROM theme_settings");
    $settings = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    echo json_encode(['success' => true, 'data' => $settings]);
    exit;
}

if ($action == 'update_theme_settings') {
    $data = json_decode(file_get_contents('php://input'), true);
    foreach ($data as $key => $value) {
        $value = $conn->real_escape_string($value);
        $conn->query("INSERT INTO theme_settings (setting_key, setting_value) VALUES ('$key', '$value') 
                      ON DUPLICATE KEY UPDATE setting_value = '$value'");
    }
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid action']);
?>