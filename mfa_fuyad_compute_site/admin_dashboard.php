<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: admin_login.php'); exit; }
include 'config.php';

// ========== JOB MANAGEMENT ==========
if (isset($_POST['add_job'])) {
    $stmt = $conn->prepare("INSERT INTO jobs (title, company, location, category, job_type, salary, positions, application_start_date, application_end_date, circular_image, apply_link, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisssss", 
        $_POST['title'], $_POST['company'], $_POST['location'], $_POST['category'], 
        $_POST['job_type'], $_POST['salary'], $_POST['positions'], 
        $_POST['application_start_date'], $_POST['application_end_date'], 
        $_POST['circular_image'], $_POST['apply_link'], $_POST['description']
    );
    $stmt->execute();
}

if (isset($_POST['edit_job'])) {
    $stmt = $conn->prepare("UPDATE jobs SET title=?, company=?, location=?, category=?, job_type=?, salary=?, positions=?, application_start_date=?, application_end_date=?, circular_image=?, apply_link=?, description=? WHERE id=?");
    $stmt->bind_param("ssssssisssssi", 
        $_POST['title'], $_POST['company'], $_POST['location'], $_POST['category'], 
        $_POST['job_type'], $_POST['salary'], $_POST['positions'], 
        $_POST['application_start_date'], $_POST['application_end_date'], 
        $_POST['circular_image'], $_POST['apply_link'], $_POST['description'], 
        $_POST['job_id']
    );
    $stmt->execute();
}

if (isset($_GET['del_job'])) {
    $conn->query("DELETE FROM jobs WHERE id=" . intval($_GET['del_job']));
}

$editJob = null;
if (isset($_GET['edit_job'])) {
    $result = $conn->query("SELECT * FROM jobs WHERE id=" . intval($_GET['edit_job']));
    $editJob = $result->fetch_assoc();
}

// ========== GALLERY MANAGEMENT ==========
if (isset($_POST['add_gallery'])) {
    $stmt = $conn->prepare("INSERT INTO gallery (title, image_path, category) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['title'], $_POST['image_path'], $_POST['category']);
    $stmt->execute();
}
if (isset($_GET['del_gallery'])) {
    $conn->query("DELETE FROM gallery WHERE id=" . intval($_GET['del_gallery']));
}

// ========== REQUEST MANAGEMENT ==========
if (isset($_GET['del_request'])) {
    $conn->query("DELETE FROM service_requests WHERE id=" . intval($_GET['del_request']));
}

// ========== FAQ MANAGEMENT ==========
if (isset($_POST['add_faq'])) {
    $stmt = $conn->prepare("INSERT INTO faqs (question, answer, category, display_order) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $_POST['question'], $_POST['answer'], $_POST['category'], $_POST['display_order']);
    $stmt->execute();
}
if (isset($_GET['del_faq'])) {
    $conn->query("DELETE FROM faqs WHERE id=" . intval($_GET['del_faq']));
}

// ========== SHOP PRODUCT MANAGEMENT ==========
if (isset($_POST['add_product'])) {
    $stmt = $conn->prepare("INSERT INTO products (name, category, price, old_price, stock, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddiss", $_POST['name'], $_POST['category'], $_POST['price'], $_POST['old_price'], $_POST['stock'], $_POST['image'], $_POST['description']);
    $stmt->execute();
}

if (isset($_POST['edit_product'])) {
    $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, old_price=?, stock=?, image=?, description=? WHERE id=?");
    $stmt->bind_param("ssddissi", $_POST['name'], $_POST['category'], $_POST['price'], $_POST['old_price'], $_POST['stock'], $_POST['image'], $_POST['description'], $_POST['product_id']);
    $stmt->execute();
}

if (isset($_GET['del_product'])) {
    $conn->query("DELETE FROM products WHERE id=" . intval($_GET['del_product']));
}

$editProduct = null;
if (isset($_GET['edit_product'])) {
    $result = $conn->query("SELECT * FROM products WHERE id=" . intval($_GET['edit_product']));
    $editProduct = $result->fetch_assoc();
}

// ========== ORDER MANAGEMENT ==========
if (isset($_POST['update_order_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE orders SET status='$status' WHERE id=$order_id");
    $success = "Order status updated successfully!";
}

// ========== PHOTOS FOLDER ==========
$photosDir = '../Photos/';
if (!is_dir($photosDir)) mkdir($photosDir, 0777, true);
if (isset($_POST['upload_photo'])) {
    $targetFile = $photosDir . basename($_FILES['photo_file']['name']);
    if (move_uploaded_file($_FILES['photo_file']['tmp_name'], $targetFile)) $success = "Photo uploaded!";
    else $error = "Upload failed";
}
if (isset($_GET['del_photo'])) {
    $fileToDelete = $photosDir . $_GET['del_photo'];
    if (file_exists($fileToDelete)) unlink($fileToDelete);
}

// Get all counts
$totalJobs = $conn->query("SELECT COUNT(*) as count FROM jobs")->fetch_assoc()['count'];
$totalRequests = $conn->query("SELECT COUNT(*) as count FROM service_requests")->fetch_assoc()['count'];
$totalBlogs = $conn->query("SELECT COUNT(*) as count FROM blog_posts")->fetch_assoc()['count'];
$totalGallery = $conn->query("SELECT COUNT(*) as count FROM gallery")->fetch_assoc()['count'];
$totalFaqs = $conn->query("SELECT COUNT(*) as count FROM faqs")->fetch_assoc()['count'];
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$totalOrders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pendingOrders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status='pending'")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM shop_users")->fetch_assoc()['count'];
$totalRevenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status='delivered'")->fetch_assoc()['total'] ?? 0;

// Get recent orders
$recentOrders = $conn->query("SELECT o.*, u.name as user_name FROM orders o JOIN shop_users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Fuyad Computer</title>
    <link rel="shortcut icon" href="../Logo/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #475569;
        }
        
        [data-theme="dark"] {
            --gray-50: #0f172a;
            --gray-100: #1e293b;
            --gray-200: #334155;
            --card-bg: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-100);
            color: var(--text-primary);
            transition: all 0.3s;
        }
        
        .admin-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 100;
        }
        
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-track { background: #1e293b; }
        .sidebar::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 10px; }
        
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: 3px solid #3b82f6;
            margin-bottom: 10px;
        }
        
        .sidebar-header h3 { font-size: 1rem; margin-bottom: 5px; }
        .sidebar-header p { font-size: 0.65rem; opacity: 0.7; }
        
        .sidebar-menu { padding: 20px 0; }
        
        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            margin: 2px 0;
        }
        
        .menu-item:hover, .menu-item.active {
            background: rgba(59,130,246,0.15);
            color: white;
            border-left-color: #3b82f6;
        }
        
        .menu-item i { width: 22px; font-size: 1.1rem; }
        .menu-item span { font-size: 0.85rem; font-weight: 500; flex: 1; }
        .menu-badge {
            background: #ef4444;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.65rem;
            margin-left: auto;
        }
        
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px 25px;
        }
        
        .top-bar {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .page-title h2 {
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-title p {
            font-size: 0.7rem;
            color: var(--text-secondary);
            margin-top: 3px;
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .logout-btn {
            background: var(--danger);
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: 0.3s;
        }
        
        .logout-btn:hover { background: #dc2626; transform: translateY(-2px); }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #d1fae5; color: #10b981; }
        .stat-icon.orange { background: #fed7aa; color: #f59e0b; }
        .stat-icon.purple { background: #e9d5ff; color: #8b5cf6; }
        .stat-icon.red { background: #fee2e2; color: #ef4444; }
        .stat-icon.cyan { background: #cffafe; color: #06b6d4; }
        
        .stat-info h3 { font-size: 1.5rem; font-weight: 700; }
        .stat-info p { font-size: 0.7rem; color: var(--text-secondary); margin-top: 3px; }
        
        .quick-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }
        
        .quick-btn {
            background: var(--card-bg);
            border: none;
            padding: 10px 20px;
            border-radius: 40px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.8rem;
            transition: 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .quick-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .card-header h3 {
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .form-group { margin-bottom: 15px; }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
        }
        
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            background: var(--card-bg);
            color: var(--text-primary);
            font-size: 0.85rem;
            transition: 0.3s;
        }
        
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); }
        .btn-success { background: var(--success); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-info { background: var(--info); color: white; }
        
        .table-responsive { overflow-x: auto; }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            text-align: left;
            padding: 12px 15px;
            background: var(--gray-100);
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.8rem;
        }
        
        .data-table tr:hover {
            background: var(--gray-100);
        }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .action-btn.edit { background: #dbeafe; color: #2563eb; }
        .action-btn.delete { background: #fee2e2; color: #ef4444; }
        .action-btn.view { background: #d1fae5; color: #10b981; }
        .action-btn.edit:hover { background: #2563eb; color: white; }
        .action-btn.delete:hover { background: #ef4444; color: white; }
        .action-btn.view:hover { background: #10b981; color: white; }
        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
        }
        
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
        .badge-shipped { background: #cffafe; color: #0891b2; }
        .badge-delivered { background: #10b981; color: white; }
        .badge-cancelled { background: #fee2e2; color: #dc2626; }
        
        .gallery-preview {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert {
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: var(--card-bg);
            border-radius: 20px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .modal-close {
            font-size: 24px;
            cursor: pointer;
            color: var(--text-secondary);
        }
        
        .modal-close:hover { color: var(--danger); }
        .modal-body { padding: 20px; }
        .modal-footer { padding: 15px 20px; border-top: 1px solid var(--gray-200); text-align: right; }
        
        .detail-row { display: flex; margin-bottom: 12px; flex-wrap: wrap; }
        .detail-label { width: 130px; font-weight: 600; color: var(--text-secondary); }
        .detail-value { flex: 1; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th, .items-table td { padding: 8px; text-align: left; border-bottom: 1px solid var(--gray-200); }
        .items-table th { background: var(--gray-100); }
        
        .mobile-toggle {
            display: none;
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
        }
        
        /* Site Control Panel Styles */
        .control-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .control-tab {
            padding: 10px 24px;
            background: var(--gray-100);
            border: none;
            border-radius: 40px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 500;
        }
        
        .control-tab:hover { background: var(--gray-200); }
        .control-tab.active { background: var(--primary); color: white; }
        
        .control-subtab { display: none; }
        .control-subtab.active { display: block; }
        
        .service-item, .review-service-item {
            background: var(--gray-100);
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .service-info, .review-service-info { flex: 1; }
        .service-title, .review-service-name { font-weight: 600; margin-bottom: 5px; }
        .service-url, .review-service-order { font-size: 12px; color: var(--gray); }
        .service-controls, .review-service-controls { display: flex; gap: 10px; }
        
        .toggle-active {
            width: 50px;
            height: 26px;
            background: #cbd5e1;
            border-radius: 30px;
            cursor: pointer;
            position: relative;
            transition: 0.3s;
        }
        .toggle-active.active { background: #10b981; }
        .toggle-active:before {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 3px;
            transition: 0.3s;
        }
        .toggle-active.active:before { left: 25px; }
        
        .color-preview {
            width: 50px;
            height: 40px;
            border-radius: 8px;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1000;
            }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 15px; }
            .mobile-toggle { display: block; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .quick-actions { justify-content: center; }
        }
             .file-upload-area {
            border: 1px dashed var(--gray-300);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            background: var(--gray-50);
        }
        .btn-sm {
            padding: 3px 8px;
            font-size: 11px;
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../Logo/logo.png" alt="Logo" onerror="this.src='https://placehold.co/65x65'">
            <h3>Fuyad Computer</h3>
            <p>Admin Control Panel</p>
        </div>
        <div class="sidebar-menu">
            <div class="menu-item active" data-tab="dashboard">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </div>
            <div class="menu-item" data-tab="jobs">
                <i class="fas fa-briefcase"></i><span>Jobs</span>
                <span class="menu-badge"><?php echo $totalJobs; ?></span>
            </div>
            <div class="menu-item" data-tab="gallery">
                <i class="fas fa-images"></i><span>Gallery</span>
                <span class="menu-badge"><?php echo $totalGallery; ?></span>
            </div>
            <div class="menu-item" data-tab="photos">
                <i class="fas fa-folder-open"></i><span>File Manager</span>
            </div>
            <div class="menu-item" data-tab="requests">
                <i class="fas fa-headset"></i><span>Support</span>
                <span class="menu-badge"><?php echo $totalRequests; ?></span>
            </div>
            <div class="menu-item" data-tab="blogs">
                <i class="fas fa-newspaper"></i><span>Blogs</span>
                <span class="menu-badge"><?php echo $totalBlogs; ?></span>
            </div>
            <div class="menu-item" data-tab="faq">
                <i class="fas fa-question-circle"></i><span>FAQ</span>
                <span class="menu-badge"><?php echo $totalFaqs; ?></span>
            </div>
            <div class="menu-item" data-tab="shop">
                <i class="fas fa-store"></i><span>Shop</span>
                <span class="menu-badge"><?php echo $pendingOrders; ?></span>
            </div>
            <div class="menu-item" data-tab="site-control">
                <i class="fas fa-globe"></i><span>Site Control</span>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
                <div class="page-title">
                    <h2 id="pageTitle"><i class="fas fa-chart-line"></i> Dashboard</h2>
                    <p id="pageSubtitle">Welcome back, <?php echo $_SESSION['admin']; ?> | <?php echo date('l, d M Y'); ?></p>
                </div>
            </div>
            <div class="admin-info">
                <div class="admin-avatar">A</div>
                <a href="admin_login.php?logout=1" class="logout-btn" onclick="return confirm('Logout?')">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Dashboard Tab -->
        <div id="dashboardTab" class="tab-content active">
            <div class="stats-grid">
                <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-briefcase"></i></div><div class="stat-info"><h3><?php echo $totalJobs; ?></h3><p>Total Jobs</p></div></div>
                <div class="stat-card"><div class="stat-icon green"><i class="fas fa-shopping-cart"></i></div><div class="stat-info"><h3><?php echo $totalOrders; ?></h3><p>Total Orders</p></div></div>
                <div class="stat-card"><div class="stat-icon orange"><i class="fas fa-users"></i></div><div class="stat-info"><h3><?php echo $totalUsers; ?></h3><p>Customers</p></div></div>
                <div class="stat-card"><div class="stat-icon purple"><i class="fas fa-images"></i></div><div class="stat-info"><h3><?php echo $totalGallery; ?></h3><p>Gallery Items</p></div></div>
                <div class="stat-card"><div class="stat-icon red"><i class="fas fa-headset"></i></div><div class="stat-info"><h3><?php echo $totalRequests; ?></h3><p>Support Tickets</p></div></div>
                <div class="stat-card"><div class="stat-icon cyan"><i class="fas fa-question-circle"></i></div><div class="stat-info"><h3><?php echo $totalFaqs; ?></h3><p>FAQs</p></div></div>
            </div>
            
            <div class="quick-actions">
                <button class="quick-btn" onclick="showTab('jobs')"><i class="fas fa-plus-circle"></i> Add New Job</button>
                <button class="quick-btn" onclick="showTab('shop')"><i class="fas fa-plus-circle"></i> Add Product</button>
                <button class="quick-btn" onclick="showTab('gallery')"><i class="fas fa-plus-circle"></i> Add Gallery Image</button>
                <button class="quick-btn" onclick="showTab('faq')"><i class="fas fa-plus-circle"></i> Add FAQ</button>
                <button class="quick-btn" onclick="window.open('../shop/index.php', '_blank')"><i class="fas fa-store"></i> Visit Shop</button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-line"></i> System Overview</h3>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 15px;">
                    <div><strong>💰 Total Revenue:</strong> ৳ <?php echo number_format($totalRevenue, 2); ?></div>
                    <div><strong>📦 Pending Orders:</strong> <?php echo $pendingOrders; ?></div>
                    <div><strong>📊 PHP Version:</strong> <?php echo phpversion(); ?></div>
                    <div><strong>🕐 Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></div>
                </div>
            </div>
        </div>
        
               <div id="jobsTab" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas <?php echo $editJob ? 'fa-edit' : 'fa-plus-circle'; ?>"></i> <?php echo $editJob ? 'Edit Job' : 'Add New Job'; ?></h3>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <?php if($editJob): ?><input type="hidden" name="job_id" value="<?php echo $editJob['id']; ?>"><?php endif; ?>
                    <div class="form-grid">
                        <div class="form-group"><label>Job Title *</label><input type="text" name="title" value="<?php echo $editJob ? htmlspecialchars($editJob['title']) : ''; ?>" required></div>
                        <div class="form-group"><label>Company Name *</label><input type="text" name="company" value="<?php echo $editJob ? htmlspecialchars($editJob['company']) : ''; ?>" required></div>
                        <div class="form-group"><label>Location</label><input type="text" name="location" value="<?php echo $editJob ? htmlspecialchars($editJob['location']) : ''; ?>"></div>
                        <div class="form-group"><label>Category *</label>
                            <select name="category" required>
                                <option value="">Select Category</option>
                                <option value="IT" <?php echo ($editJob && $editJob['category']=='IT') ? 'selected' : ''; ?>>💻 IT & Software</option>
                                <option value="Bank" <?php echo ($editJob && $editJob['category']=='Bank') ? 'selected' : ''; ?>>🏦 Bank & Finance</option>
                                <option value="Govt" <?php echo ($editJob && $editJob['category']=='Govt') ? 'selected' : ''; ?>>🏛️ Government</option>
                                <option value="NGO" <?php echo ($editJob && $editJob['category']=='NGO') ? 'selected' : ''; ?>>🤝 NGO</option>
                                <option value="Education" <?php echo ($editJob && $editJob['category']=='Education') ? 'selected' : ''; ?>>📚 Education</option>
                                <option value="Medical" <?php echo ($editJob && $editJob['category']=='Medical') ? 'selected' : ''; ?>>🏥 Medical</option>
                                <option value="Engineering" <?php echo ($editJob && $editJob['category']=='Engineering') ? 'selected' : ''; ?>>⚙️ Engineering</option>
                                <option value="Marketing" <?php echo ($editJob && $editJob['category']=='Marketing') ? 'selected' : ''; ?>>📢 Marketing</option>
                                <option value="HR" <?php echo ($editJob && $editJob['category']=='HR') ? 'selected' : ''; ?>>👥 HR</option>
                                <option value="Others" <?php echo ($editJob && $editJob['category']=='Others') ? 'selected' : ''; ?>>📌 Others</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Job Type *</label>
                            <select name="job_type" required>
                                <option value="govt" <?php echo ($editJob && $editJob['job_type']=='govt') ? 'selected' : ''; ?>>🏛️ সরকারি</option>
                                <option value="private" <?php echo ($editJob && $editJob['job_type']=='private') ? 'selected' : ''; ?>>🏢 বেসরকারি</option>
                                <option value="ngo" <?php echo ($editJob && $editJob['job_type']=='ngo') ? 'selected' : ''; ?>>🤝 এনজিও</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Salary</label><input type="text" name="salary" value="<?php echo $editJob ? htmlspecialchars($editJob['salary']) : ''; ?>" placeholder="25000-35000"></div>
                        <div class="form-group"><label>Positions</label><input type="number" name="positions" value="<?php echo $editJob ? $editJob['positions'] : '1'; ?>"></div>
                        <div class="form-group"><label>Start Date</label><input type="date" name="application_start_date" value="<?php echo $editJob ? $editJob['application_start_date'] : ''; ?>"></div>
                        <div class="form-group"><label>End Date *</label><input type="date" name="application_end_date" value="<?php echo $editJob ? $editJob['application_end_date'] : ''; ?>" required></div>
                        <div class="form-group"><label>Circular Image URL</label><input type="url" name="circular_image" value="<?php echo $editJob ? htmlspecialchars($editJob['circular_image']) : ''; ?>" placeholder="https://..."></div>
                        <div class="form-group"><label>Apply Link *</label><input type="url" name="apply_link" value="<?php echo $editJob ? htmlspecialchars($editJob['apply_link']) : ''; ?>" required></div>
                        <div class="form-group"><label>Description</label><textarea name="description" rows="4"><?php echo $editJob ? htmlspecialchars($editJob['description']) : ''; ?></textarea></div>

                        <!-- File Upload Section -->
                        <div class="form-group">
                            <label>Official Circular File (PDF/Image)</label>
                            <div class="file-upload-area">
                                <input type="file" id="jobFile" name="job_file" accept=".jpg,.jpeg,.png,.gif,.pdf" style="display:none;">
                                <button type="button" class="btn btn-info" onclick="document.getElementById('jobFile').click()">
                                    <i class="fas fa-upload"></i> Choose File
                                </button>
                                <span id="fileName" style="margin-left: 10px;">No file selected</span>
                                <div id="filePreview" style="margin-top: 10px;"></div>
                            </div>
                            <?php if($editJob && $editJob['circular_file']): ?>
                                <div id="existingFile" style="margin-top: 10px; padding: 10px; background: var(--gray-100); border-radius: 8px;">
                                    <i class="fas fa-file"></i> Current: <?php echo basename($editJob['circular_file']); ?>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteJobFile(<?php echo $editJob['id']; ?>)" style="margin-left: 10px; padding: 2px 10px;">Remove</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="submit" name="<?php echo $editJob ? 'edit_job' : 'add_job'; ?>" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $editJob ? 'Update Job' : 'Publish Job'; ?></button>
                    <?php if($editJob): ?><a href="admin_dashboard.php" class="btn btn-warning">Cancel Edit</a><?php endif; ?>
                </form>
            </div>

            <div class="card">
                <div class="card-header"><h3><i class="fas fa-list"></i> Existing Jobs</h3></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Deadline</th>
                                <th>Circular</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $jobs = $conn->query("SELECT * FROM jobs ORDER BY created_at DESC");
                            while ($job = $jobs->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo $job['title']; ?></strong></td>
                                <td><?php echo $job['company']; ?></td>
                                <td><?php echo $job['category']; ?></td>
                                <td><?php echo $job['job_type']; ?></td>
                                <td><?php echo $job['application_end_date']; ?></td>
                                <td>
                                    <?php if($job['circular_file']): ?>
                                        <?php if($job['circular_file_type'] == 'pdf'): ?>
                                            <a href="<?php echo $job['circular_file']; ?>" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
                                        <?php else: ?>
                                            <a href="<?php echo $job['circular_file']; ?>" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-image"></i> View</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-pending">No file</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-btns">
                                    <a href="?edit_job=<?php echo $job['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                                    <a href="?del_job=<?php echo $job['id']; ?>" class="action-btn delete" onclick="return confirm('Delete this job?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Shop Tab -->
        <div id="shopTab" class="tab-content">
            <div class="stats-grid">
                <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-box"></i></div><div class="stat-info"><h3><?php echo $totalProducts; ?></h3><p>Products</p></div></div>
                <div class="stat-card"><div class="stat-icon green"><i class="fas fa-shopping-cart"></i></div><div class="stat-info"><h3><?php echo $totalOrders; ?></h3><p>Orders</p></div></div>
                <div class="stat-card"><div class="stat-icon orange"><i class="fas fa-users"></i></div><div class="stat-info"><h3><?php echo $totalUsers; ?></h3><p>Customers</p></div></div>
            </div>
            
            <div class="card">
                <div class="card-header"><h3><i class="fas <?php echo $editProduct ? 'fa-edit' : 'fa-plus-circle'; ?>"></i> <?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?></h3></div>
                <form method="POST">
                    <?php if($editProduct): ?><input type="hidden" name="product_id" value="<?php echo $editProduct['id']; ?>"><?php endif; ?>
                    <div class="form-grid">
                        <div class="form-group"><label>Product Name</label><input type="text" name="name" value="<?php echo $editProduct ? htmlspecialchars($editProduct['name']) : ''; ?>" required></div>
                        <div class="form-group"><label>Category</label><select name="category" required>
                            <option value="কলম" <?php echo ($editProduct && $editProduct['category']=='কলম') ? 'selected' : ''; ?>>কলম</option>
                            <option value="খাতা" <?php echo ($editProduct && $editProduct['category']=='খাতা') ? 'selected' : ''; ?>>খাতা</option>
                            <option value="স্টেশনারী" <?php echo ($editProduct && $editProduct['category']=='স্টেশনারী') ? 'selected' : ''; ?>>স্টেশনারী</option>
                            <option value="এক্সেসরিজ" <?php echo ($editProduct && $editProduct['category']=='এক্সেসরিজ') ? 'selected' : ''; ?>>এক্সেসরিজ</option>
                        </select></div>
                        <div class="form-group"><label>Price (৳)</label><input type="number" step="0.01" name="price" value="<?php echo $editProduct ? $editProduct['price'] : ''; ?>" required></div>
                        <div class="form-group"><label>Old Price</label><input type="number" step="0.01" name="old_price" value="<?php echo $editProduct ? $editProduct['old_price'] : ''; ?>"></div>
                        <div class="form-group"><label>Stock</label><input type="number" name="stock" value="<?php echo $editProduct ? $editProduct['stock'] : '10'; ?>"></div>
                        <div class="form-group"><label>Image URL</label><input type="url" name="image" value="<?php echo $editProduct ? htmlspecialchars($editProduct['image']) : ''; ?>" placeholder="https://placehold.co/400x400"></div>
                        <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?php echo $editProduct ? htmlspecialchars($editProduct['description']) : ''; ?></textarea></div>
                    </div>
                    <button type="submit" name="<?php echo $editProduct ? 'edit_product' : 'add_product'; ?>" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $editProduct ? 'Update Product' : 'Add Product'; ?></button>
                    <?php if($editProduct): ?><a href="admin_dashboard.php" class="btn btn-warning">Cancel Edit</a><?php endif; ?>
                </form>
            </div>
            
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-list"></i> Products</h3></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php $products = $conn->query("SELECT * FROM products ORDER BY id DESC");
                            while ($product = $products->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><img src="<?php echo $product['image']; ?>" width="40" height="40" style="object-fit:cover; border-radius:8px;"></td>
                                <td><?php echo $product['name']; ?></td>
                                <td>৳ <?php echo $product['price']; ?></td>
                                <td><?php echo $product['stock']; ?></td>
                                <td class="action-btns">
                                    <a href="?edit_product=<?php echo $product['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                                    <a href="?del_product=<?php echo $product['id']; ?>" class="action-btn delete" onclick="return confirm('Delete this product?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-shopping-cart"></i> Recent Orders</h3></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Order No</th><th>Customer</th><th>Total</th><th>Payment</th><th>TRX ID</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php $orders = $conn->query("SELECT o.*, u.name as user_name FROM orders o JOIN shop_users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 20");
                            while ($order = $orders->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo $order['order_no']; ?></strong></td>
                                <td><?php echo $order['user_name']; ?></td>
                                <td>৳ <?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php $methods = ['bkash'=>'bKash', 'nagad'=>'নগদ', 'rocket'=>'রকেট', 'cash_on_delivery'=>'নগদ (ডেলিভারিতে)']; echo $methods[$order['payment_method']] ?? $order['payment_method']; ?></td>
                                <td><?php if($order['trx_id']): ?><span class="badge badge-approved"><?php echo $order['trx_id']; ?></span><?php else: ?><span class="badge badge-pending">N/A</span><?php endif; ?></td>
                                <td>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Change order status?');">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="status" onchange="this.form.submit()" style="padding:5px 10px; border-radius:8px; cursor:pointer;">
                                            <option value="pending" <?php echo $order['status']=='pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                            <option value="processing" <?php echo $order['status']=='processing' ? 'selected' : ''; ?>>⚙️ Processing</option>
                                            <option value="shipped" <?php echo $order['status']=='shipped' ? 'selected' : ''; ?>>📦 Shipped</option>
                                            <option value="delivered" <?php echo $order['status']=='delivered' ? 'selected' : ''; ?>>✅ Delivered</option>
                                            <option value="cancelled" <?php echo $order['status']=='cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
                                        </select>
                                        <input type="hidden" name="update_order_status" value="1">
                                    </form>
                                </td>
                                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                <td><button class="action-btn view" onclick="viewOrderDetails(<?php echo $order['id']; ?>)"><i class="fas fa-eye"></i></button></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Gallery Tab -->
        <div id="galleryTab" class="tab-content">
            <div class="card"><div class="card-header"><h3><i class="fas fa-plus-circle"></i> Add Gallery Image</h3></div>
                <form method="POST"><div class="form-grid">
                    <div class="form-group"><label>Image Title</label><input type="text" name="title" required></div>
                    <div class="form-group"><label>Image URL</label><input type="url" name="image_path" required></div>
                    <div class="form-group"><label>Category</label><select name="category"><option value="shop">দোকান</option><option value="work">কাজের নমুনা</option><option value="certificate">সার্টিফিকেট</option><option value="team">আমাদের টিম</option></select></div>
                </div><button type="submit" name="add_gallery" class="btn btn-primary">Add Image</button></form>
            </div>
            <div class="card"><div class="card-header"><h3><i class="fas fa-images"></i> Gallery Images</h3></div>
                <div class="table-responsive"><table class="data-table"><thead><tr><th>Preview</th><th>Title</th><th>Category</th><th>Actions</th></tr></thead>
                <tbody><?php $gallery = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
                while ($img = $gallery->fetch_assoc()): ?>
                <tr><td><img src="<?php echo $img['image_path']; ?>" class="gallery-preview"></td>
                <td><?php echo $img['title']; ?></td><td><?php echo $img['category']; ?></td>
                <td><a href="?del_gallery=<?php echo $img['id']; ?>" class="action-btn delete" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a></td></tr>
                <?php endwhile; ?></tbody></table></div>
            </div>
        </div>
        
        <!-- Photos Tab -->
        <div id="photosTab" class="tab-content">
            <div class="card"><div class="card-header"><h3><i class="fas fa-upload"></i> Upload Photo</h3></div>
                <form method="POST" enctype="multipart/form-data"><input type="file" name="photo_file" accept="image/*" required>
                <button type="submit" name="upload_photo" class="btn btn-primary">Upload</button></form>
            </div>
            <div class="card"><div class="card-header"><h3><i class="fas fa-folder-open"></i> Photos Folder</h3></div>
                <div class="table-responsive"><table class="data-table"><thead><tr><th>Preview</th><th>Filename</th><th>Size</th><th>Actions</th></tr></thead>
                <tbody><?php $photos = scandir($photosDir);
                foreach ($photos as $photo): if ($photo != '.' && $photo != '..' && !is_dir($photosDir . $photo)): $size = round(filesize($photosDir . $photo) / 1024, 2); ?>
                <tr><td><img src="../Photos/<?php echo $photo; ?>" class="gallery-preview"></td>
                <td><?php echo $photo; ?></td><td><?php echo $size; ?> KB</td>
                <td><a href="?del_photo=<?php echo $photo; ?>" class="action-btn delete" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a></td></tr>
                <?php endif; endforeach; ?></tbody></table></div>
            </div>
        </div>
        
        <!-- Requests Tab -->
        <div id="requestsTab" class="tab-content">
            <div class="card"><div class="card-header"><h3><i class="fas fa-ticket-alt"></i> Support Requests</h3></div>
                <div class="table-responsive"><table class="data-table"><thead><tr><th>Date</th><th>Name</th><th>Phone</th><th>Service</th><th>Priority</th><th>Message</th><th>Actions</th></tr></thead>
                <tbody><?php $requests = $conn->query("SELECT * FROM service_requests ORDER BY request_date DESC");
                while ($req = $requests->fetch_assoc()): ?>
                <tr><td><?php echo $req['request_date']; ?></td><td><strong><?php echo $req['name']; ?></strong></td>
                <td><?php echo $req['phone']; ?></td><td><?php echo $req['service']; ?></td>
                <td><span class="badge badge-pending"><?php echo $req['priority']; ?></span></td>
                <td><?php echo substr($req['message'], 0, 40); ?>...</td>
                <td><a href="?del_request=<?php echo $req['id']; ?>" class="action-btn delete"><i class="fas fa-trash"></i></a></td></tr>
                <?php endwhile; ?></tbody></table></div>
            </div>
        </div>
        
        <!-- Blogs Tab -->
        <div id="blogsTab" class="tab-content">
            <div class="card"><div class="card-header"><h3><i class="fas fa-newspaper"></i> Blog Moderation</h3></div>
                <div class="table-responsive"><table class="data-table"><thead><tr><th>Date</th><th>Author</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody><?php $posts = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
                while ($post = $posts->fetch_assoc()): ?>
                <tr><td><?php echo $post['created_at']; ?></td><td><?php echo $post['author']; ?></td>
                <td><?php echo substr($post['title'], 0, 40); ?>...</td>
                <td><span class="badge <?php echo $post['status']=='approved'?'badge-approved':'badge-pending'; ?>"><?php echo $post['status']; ?></span></td>
                <td><a href="blog_admin.php?approve=<?php echo $post['id']; ?>" class="action-btn edit"><i class="fas fa-check"></i></a>
                <a href="blog_admin.php?delete=<?php echo $post['id']; ?>" class="action-btn delete"><i class="fas fa-trash"></i></a></td></tr>
                <?php endwhile; ?></tbody></table></div>
            </div>
        </div>
        
        <!-- FAQ Tab -->
        <div id="faqTab" class="tab-content">
            <div class="card"><div class="card-header"><h3><i class="fas fa-plus-circle"></i> Add FAQ</h3></div>
                <form method="POST"><div class="form-grid">
                    <div class="form-group"><label>Question</label><input type="text" name="question" required></div>
                    <div class="form-group"><label>Category</label><select name="category"><option value="general">General</option><option value="service">Service</option><option value="payment">Payment</option></select></div>
                    <div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="0"></div>
                    <div class="form-group"><label>Answer</label><textarea name="answer" rows="4" required></textarea></div>
                </div><button type="submit" name="add_faq" class="btn btn-primary">Add FAQ</button></form>
            </div>
            <div class="card"><div class="card-header"><h3><i class="fas fa-list"></i> FAQs</h3></div>
                <div class="table-responsive"><table class="data-table"><thead><tr><th>Question</th><th>Category</th><th>Order</th><th>Actions</th></tr></thead>
                <tbody><?php $faqs = $conn->query("SELECT * FROM faqs ORDER BY category, display_order");
                while ($faq = $faqs->fetch_assoc()): ?>
                <tr><td><strong><?php echo substr($faq['question'], 0, 50); ?></strong></td><td><?php echo $faq['category']; ?></td><td><?php echo $faq['display_order']; ?></td>
                <td><a href="?del_faq=<?php echo $faq['id']; ?>" class="action-btn delete"><i class="fas fa-trash"></i></a></td></tr>
                <?php endwhile; ?></tbody></table></div>
            </div>
        </div>
        
        <!-- Site Control Tab -->
        <div id="siteControlTab" class="tab-content">
            <div class="control-tabs">
                <button class="control-tab active" data-tab="general"><i class="fas fa-sliders-h"></i> General</button>
                <button class="control-tab" data-tab="header"><i class="fas fa-header"></i> Header & Hero</button>
                <button class="control-tab" data-tab="services"><i class="fas fa-cogs"></i> Services</button>
                <button class="control-tab" data-tab="review-services"><i class="fas fa-star"></i> Review Services</button>
                <button class="control-tab" data-tab="theme"><i class="fas fa-palette"></i> Theme & Colors</button>
            </div>
            
            <div id="general-tab" class="control-subtab active">
                <div class="card"><div class="card-header"><h3><i class="fas fa-sliders-h"></i> General Settings</h3><button class="btn btn-primary" onclick="saveGeneralSettings()"><i class="fas fa-save"></i> Save All</button></div>
                    <div class="form-grid">
                        <div class="form-group"><label>Site Title</label><input type="text" id="site_title" class="site-setting" data-key="site_title"></div>
                        <div class="form-group"><label>Site Logo URL</label><input type="text" id="site_logo" class="site-setting" data-key="site_logo"></div>
                        <div class="form-group"><label>Hero Title</label><input type="text" id="hero_title" class="site-setting" data-key="hero_title"></div>
                        <div class="form-group"><label>Hero Subtitle</label><input type="text" id="hero_subtitle" class="site-setting" data-key="hero_subtitle"></div>
                        <div class="form-group"><label>Hero Badge Text</label><input type="text" id="hero_badge" class="site-setting" data-key="hero_badge"></div>
                                                <div class="form-group"><label>Hero Description</label><textarea id="hero_description" class="site-setting" data-key="hero_description" rows="2"></textarea></div>
                        <div class="form-group"><label>Typing Words (comma separated)</label><input type="text" id="typing_words" class="site-setting" data-key="typing_words" placeholder="Word1, Word2, Word3"></div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header"><h3><i class="fas fa-chart-line"></i> Stats Settings</h3></div>
                    <div class="form-grid">
                        <div class="form-group"><label>Happy Clients Count</label><input type="number" id="happy_clients" class="site-setting" data-key="happy_clients"></div>
                        <div class="form-group"><label>Jobs Done Count</label><input type="number" id="jobs_done" class="site-setting" data-key="jobs_done"></div>
                        <div class="form-group"><label>Years Experience</label><input type="number" id="years_experience" class="site-setting" data-key="years_experience"></div>
                        <div class="form-group"><label>Total Services</label><input type="number" id="total_services" class="site-setting" data-key="total_services"></div>
                    </div>
                </div>
            </div>
            
            <div id="header-tab" class="control-subtab">
                <div class="card">
                    <div class="card-header"><h3><i class="fas fa-header"></i> Header Settings</h3><button class="btn btn-primary" onclick="saveGeneralSettings()"><i class="fas fa-save"></i> Save All</button></div>
                    <div class="form-grid">
                        <div class="form-group"><label>Company Name</label><input type="text" id="company_name" class="site-setting" data-key="company_name"></div>
                        <div class="form-group"><label>Company Tagline</label><input type="text" id="company_tagline" class="site-setting" data-key="company_tagline"></div>
                        <div class="form-group"><label>Header Background Color</label><input type="color" id="header_bg" class="site-setting" data-key="header_bg"></div>
                        <div class="form-group"><label>Navbar Text Color</label><input type="color" id="nav_text_color" class="site-setting" data-key="nav_text_color"></div>
                    </div>
                </div>
            </div>
            
            <div id="services-tab" class="control-subtab">
                <div class="card">
                    <div class="card-header"><h3><i class="fas fa-plus-circle"></i> Add New Service</h3></div>
                    <div class="form-grid">
                        <div class="form-group"><label>Service Title</label><input type="text" id="new_service_title" placeholder="e.g., New Service"></div>
                        <div class="form-group"><label>Service URL</label><input type="text" id="new_service_url" placeholder="https://... or page.php"></div>
                        <div class="form-group"><label>Icon Class</label><input type="text" id="new_service_icon" placeholder="fas fa-star"></div>
                        <div class="form-group"><label>Display Order</label><input type="number" id="new_service_order" value="0"></div>
                    </div>
                    <button class="btn btn-success" onclick="addNewService()"><i class="fas fa-plus"></i> Add Service</button>
                </div>
                
                <div class="card">
                    <div class="card-header"><h3><i class="fas fa-list"></i> Manage Services</h3></div>
                    <div id="services-list"></div>
                </div>
            </div>
            
            <div id="review-services-tab" class="control-subtab">
                <div class="card">
                    <div class="card-header"><h3><i class="fas fa-plus-circle"></i> Add Review Category</h3></div>
                    <div class="form-grid">
                        <div class="form-group"><label>Service Name</label><input type="text" id="new_review_service" placeholder="e.g., New Service"></div>
                        <div class="form-group"><label>Display Order</label><input type="number" id="new_review_order" value="0"></div>
                    </div>
                    <button class="btn btn-success" onclick="addNewReviewService()"><i class="fas fa-plus"></i> Add Category</button>
                </div>
                
                <div class="card">
                    <div class="card-header"><h3><i class="fas fa-list"></i> Review Categories</h3></div>
                    <div id="review-services-list"></div>
                </div>
            </div>
            
            <div id="theme-tab" class="control-subtab">
                <div class="card">
                    <div class="card-header"><h3><i class="fas fa-palette"></i> Color & Layout Settings</h3><button class="btn btn-primary" onclick="saveThemeSettings()"><i class="fas fa-save"></i> Save Theme</button></div>
                    <div class="form-grid">
                        <div class="form-group"><label>Primary Color</label><input type="color" id="primary_color" data-key="primary_color" onchange="previewColor()"></div>
                        <div class="form-group"><label>Secondary Color</label><input type="color" id="secondary_color" data-key="secondary_color"></div>
                        <div class="form-group"><label>Layout Style</label><select id="layout_style" data-key="layout_style"><option value="modern">Modern</option><option value="classic">Classic</option><option value="minimal">Minimal</option></select></div>
                        <div class="form-group"><label>Dark Mode Default</label><select id="dark_mode" data-key="dark_mode"><option value="0">Light</option><option value="1">Dark</option></select></div>
                        <div class="form-group"><label>Border Radius</label><select id="border_radius" data-key="border_radius"><option value="12px">Small (12px)</option><option value="16px">Medium (16px)</option><option value="24px">Large (24px)</option><option value="30px">Extra Large (30px)</option></select></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-shopping-cart"></i> Order Details</h3>
            <span class="modal-close" onclick="closeOrderModal()">&times;</span>
        </div>
        <div class="modal-body" id="orderDetailsBody">Loading...</div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeOrderModal()">Close</button>
        </div>
    </div>
</div>

<script>
    // Tab switching
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
        var selectedTab = document.getElementById(tabName + 'Tab');
        if (selectedTab) selectedTab.classList.add('active');
        var selectedMenuItem = document.querySelector('.menu-item[data-tab="' + tabName + '"]');
        if (selectedMenuItem) selectedMenuItem.classList.add('active');
        
        var titles = {
            dashboard: 'Dashboard', jobs: 'Job Management', shop: 'Shop Management',
            gallery: 'Gallery', photos: 'File Manager', requests: 'Support Tickets',
            blogs: 'Blog Management', faq: 'FAQ Management', 'site-control': 'Site Control Panel'
        };
        var pageTitle = document.getElementById('pageTitle');
        if (pageTitle) {
            var icon = 'cog';
            if (tabName === 'dashboard') icon = 'chart-line';
            else if (tabName === 'jobs') icon = 'briefcase';
            else if (tabName === 'shop') icon = 'store';
            else if (tabName === 'site-control') icon = 'globe';
            pageTitle.innerHTML = '<i class="fas fa-' + icon + '"></i> ' + (titles[tabName] || 'Dashboard');
        }
    }
    
    document.querySelectorAll('.menu-item').forEach(function(item) {
        item.addEventListener('click', function() { var tab = this.getAttribute('data-tab'); if(tab) showTab(tab); });
    });
    
    document.getElementById('mobileToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('open');
    });
    
    // Order Details
    function viewOrderDetails(orderId) {
        fetch('shop_api.php?action=get_order_details&id=' + orderId)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    var order = data.order;
                    var itemsHtml = '';
                    if (order.items) {
                        for (var i = 0; i < order.items.length; i++) {
                            var item = order.items[i];
                            itemsHtml += '<tr><td>' + escapeHtml(item.name) + '</td><td>' + item.quantity + '</td><td>৳ ' + parseFloat(item.price).toFixed(2) + '</td><td>৳ ' + (item.quantity * item.price).toFixed(2) + '</td></tr>';
                        }
                    }
                    var paymentMethods = { 'bkash': 'bKash', 'nagad': 'নগদ', 'rocket': 'রকেট', 'cash_on_delivery': 'নগদ (ডেলিভারিতে)' };
                    var paymentMethod = paymentMethods[order.payment_method] || order.payment_method;
                    var html = '<div class="detail-row"><div class="detail-label">Order Number:</div><div class="detail-value"><strong>' + escapeHtml(order.order_no) + '</strong></div></div>' +
                        '<div class="detail-row"><div class="detail-label">Customer Name:</div><div class="detail-value">' + escapeHtml(order.customer_name) + '</div></div>' +
                        '<div class="detail-row"><div class="detail-label">Email:</div><div class="detail-value">' + escapeHtml(order.customer_email) + '</div></div>' +
                        '<div class="detail-row"><div class="detail-label">Phone:</div><div class="detail-value">' + escapeHtml(order.phone) + '</div></div>' +
                        '<div class="detail-row"><div class="detail-label">Address:</div><div class="detail-value">' + escapeHtml(order.shipping_address) + '</div></div>' +
                        '<div class="detail-row"><div class="detail-label">Payment Method:</div><div class="detail-value">' + paymentMethod + '</div></div>' +
                        '<div class="detail-row"><div class="detail-label">Transaction ID:</div><div class="detail-value"><strong>' + (order.trx_id ? order.trx_id : 'N/A') + '</strong></div></div>' +
                        '<div class="detail-row"><div class="detail-label">Status:</div><div class="detail-value"><span class="badge badge-' + order.status + '">' + order.status + '</span></div></div>' +
                        '<div class="detail-row"><div class="detail-label">Order Date:</div><div class="detail-value">' + order.created_at + '</div></div>' +
                        '<h4 style="margin-top: 15px;">Order Items</h4>' +
                        '<table class="items-table"><thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>' + itemsHtml + '</tbody>' +
                        '<tfoot><tr><td colspan="3" style="text-align:right;"><strong>Total:</strong></td><td><strong>৳ ' + parseFloat(order.total_amount).toFixed(2) + '</strong></td></tr></tfoot></table>';
                    document.getElementById('orderDetailsBody').innerHTML = html;
                    document.getElementById('orderModal').style.display = 'flex';
                } else { alert('Failed to load order details'); }
            })
            .catch(function(err) { console.error('Error:', err); alert('Error loading order details'); });
    }
    
    function closeOrderModal() { document.getElementById('orderModal').style.display = 'none'; }
    function escapeHtml(text) { if (!text) return ''; var div = document.createElement('div'); div.textContent = text; return div.innerHTML; }
    window.onclick = function(event) { var modal = document.getElementById('orderModal'); if (event.target === modal) closeOrderModal(); }
    
    // Site Control Functions
    function loadAllSettings() { loadSiteSettings(); loadServices(); loadReviewServices(); loadThemeSettings(); }
    
    function loadSiteSettings() {
        fetch('site_control_api.php?action=get_site_settings')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    for (var key in data.data) {
                        var input = document.querySelector('[data-key="' + key + '"]');
                        if (input) {
                            if (key === 'typing_words') {
                                try { var words = JSON.parse(data.data[key]); input.value = words.join(', '); } catch(e) { input.value = data.data[key]; }
                            } else { input.value = data.data[key]; }
                        }
                    }
                }
            });
    }
    
    function saveGeneralSettings() {
        var settings = {};
        document.querySelectorAll('.site-setting').forEach(function(input) {
            var key = input.getAttribute('data-key') || input.id;
            var value = input.value;
            if (key === 'typing_words') { value = JSON.stringify(value.split(',').map(function(w) { return w.trim(); })); }
            settings[key] = value;
        });
        fetch('site_control_api.php?action=update_site_settings', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(settings)
        }).then(function(res) { return res.json(); }).then(function(data) { if (data.success) alert('Settings saved!'); else alert('Error'); });
    }
    
    function loadServices() {
        fetch('site_control_api.php?action=get_services')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    var container = document.getElementById('services-list');
                    if (container) {
                        var html = '';
                        for (var i = 0; i < data.data.length; i++) {
                            var s = data.data[i];
                            html += '<div class="service-item" data-id="' + s.id + '">' +
                                '<div class="service-info"><div class="service-title">' + escapeHtml(s.service_title) + '</div>' +
                                '<div class="service-url">' + escapeHtml(s.service_url) + ' | Icon: ' + s.service_icon + ' | Order: ' + s.display_order + '</div></div>' +
                                '<div class="service-controls">' +
                                '<div class="toggle-active ' + (s.is_active == 1 ? 'active' : '') + '" onclick="toggleService(' + s.id + ', this)"></div>' +
                                '<button class="action-btn edit" onclick="editService(' + s.id + ')"><i class="fas fa-edit"></i></button>' +
                                '<button class="action-btn delete" onclick="deleteService(' + s.id + ')"><i class="fas fa-trash"></i></button>' +
                                '</div></div>';
                        }
                        container.innerHTML = html;
                    }
                }
            });
    }
    
    function addNewService() {
        var data = {
            title: document.getElementById('new_service_title').value,
            url: document.getElementById('new_service_url').value,
            icon: document.getElementById('new_service_icon').value,
            order: parseInt(document.getElementById('new_service_order').value)
        };
        fetch('site_control_api.php?action=add_service', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
        }).then(function(res) { return res.json(); }).then(function(data) { if (data.success) { alert('Service added!'); loadServices(); } });
    }
    
    function deleteService(id) {
        if (confirm('Delete this service?')) {
            fetch('site_control_api.php?action=delete_service', {
                method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: id })
            }).then(function(res) { return res.json(); }).then(function(data) { if (data.success) loadServices(); });
        }
    }
    
    function loadReviewServices() {
        fetch('site_control_api.php?action=get_review_services')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    var container = document.getElementById('review-services-list');
                    if (container) {
                        var html = '';
                        for (var i = 0; i < data.data.length; i++) {
                            var s = data.data[i];
                            html += '<div class="review-service-item" data-id="' + s.id + '">' +
                                '<div class="review-service-info"><div class="review-service-name">' + escapeHtml(s.service_name) + '</div>' +
                                '<div class="review-service-order">Order: ' + s.display_order + '</div></div>' +
                                '<div class="review-service-controls">' +
                                '<div class="toggle-active ' + (s.is_active == 1 ? 'active' : '') + '" onclick="toggleReviewService(' + s.id + ', this)"></div>' +
                                '<button class="action-btn edit" onclick="editReviewService(' + s.id + ')"><i class="fas fa-edit"></i></button>' +
                                '<button class="action-btn delete" onclick="deleteReviewService(' + s.id + ')"><i class="fas fa-trash"></i></button>' +
                                '</div></div>';
                        }
                        container.innerHTML = html;
                    }
                }
            });
    }
    
    function addNewReviewService() {
        var data = { name: document.getElementById('new_review_service').value, order: parseInt(document.getElementById('new_review_order').value) };
        fetch('site_control_api.php?action=add_review_service', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
        }).then(function(res) { return res.json(); }).then(function(data) { if (data.success) { alert('Category added!'); loadReviewServices(); } });
    }
    
    function deleteReviewService(id) {
        if (confirm('Delete this category?')) {
            fetch('site_control_api.php?action=delete_review_service', {
                method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: id })
            }).then(function(res) { return res.json(); }).then(function(data) { if (data.success) loadReviewServices(); });
        }
    }
    
    function loadThemeSettings() {
        fetch('site_control_api.php?action=get_theme_settings')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    for (var key in data.data) {
                        var input = document.querySelector('[data-key="' + key + '"]');
                        if (input) input.value = data.data[key];
                    }
                }
            });
    }
    
    function saveThemeSettings() {
        var settings = {};
        document.querySelectorAll('[data-key]').forEach(function(input) {
            settings[input.getAttribute('data-key')] = input.value;
        });
        fetch('site_control_api.php?action=update_theme_settings', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(settings)
        }).then(function(res) { return res.json(); }).then(function(data) { if (data.success) alert('Theme saved!'); });
    }
    
    function previewColor() { var primary = document.getElementById('primary_color').value; document.documentElement.style.setProperty('--primary', primary); }
    
    // Control tabs
    document.querySelectorAll('.control-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.control-tab').forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');
            var tabId = this.getAttribute('data-tab');
            document.querySelectorAll('.control-subtab').forEach(function(sub) { sub.classList.remove('active'); });
            var activeTab = document.getElementById(tabId + '-tab');
            if (activeTab) activeTab.classList.add('active');
        });
    });
    
    loadAllSettings();
    // File upload handling
document.getElementById('jobFile')?.addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        document.getElementById('fileName').innerHTML = '<i class="fas fa-file"></i> ' + file.name;
        
        // Preview for images
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('filePreview').innerHTML = '<img src="' + e.target.result + '" style="max-width:200px; max-height:150px; border-radius:8px; border:1px solid #e2e8f0;">';
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            document.getElementById('filePreview').innerHTML = '<div style="padding:10px; background:#f1f5f9; border-radius:8px;"><i class="fas fa-file-pdf fa-3x" style="color:#ef4444;"></i><p>' + file.name + '</p></div>';
        } else {
            document.getElementById('filePreview').innerHTML = '<div style="padding:10px; background:#f1f5f9; border-radius:8px;"><i class="fas fa-file fa-3x"></i><p>' + file.name + '</p></div>';
        }
        
        // Upload file via AJAX
        var formData = new FormData();
        formData.append('job_file', file);
        var jobId = document.querySelector('input[name="job_id"]')?.value || '0';
        formData.append('job_id', jobId);
        
        fetch('upload_job_file.php?action=upload_job_file', {
            method: 'POST',
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                console.log('File uploaded:', data.file_path);
                document.getElementById('filePreview').innerHTML += '<p style="color:green; margin-top:5px;"><i class="fas fa-check-circle"></i> Uploaded successfully!</p>';
            } else {
                alert('Upload failed: ' + data.error);
            }
        })
        .catch(function(err) { console.error('Upload error:', err); });
    }
});

// Delete job file function
function deleteJobFile(jobId) {
    if (confirm('Delete this circular file?')) {
        fetch('upload_job_file.php?action=delete_job_file', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ job_id: jobId })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert('Delete failed: ' + (data.error || 'Unknown error'));
            }
        });
    }
}
</script>
</body>
</html>