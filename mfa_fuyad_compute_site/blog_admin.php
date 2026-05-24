<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: admin_login.php'); exit; }
include 'config.php';

if (isset($_GET['approve'])) {
    $conn->query("UPDATE blog_posts SET status='approved' WHERE id=" . intval($_GET['approve']));
}
if (isset($_GET['reject'])) {
    $conn->query("UPDATE blog_posts SET status='rejected' WHERE id=" . intval($_GET['reject']));
}
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM blog_posts WHERE id=" . intval($_GET['delete']));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Blogs</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .blog-item { background: var(--bg-card); border-radius: 15px; padding: 15px; margin-bottom: 15px; }
        .blog-actions a { margin-right: 10px; text-decoration: none; }
        .approve { color: #10b981; }
        .reject { color: #f59e0b; }
        .delete { color: #ef4444; }
    </style>
</head>
<body>
<div class="container-premium">
    <h1>Blog Moderation</h1>
    <a href="admin_dashboard.php" style="display:inline-block; margin-bottom:20px;">← Back to Dashboard</a>
    
    <?php
    $posts = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
    while ($p = $posts->fetch_assoc()) {
        echo "<div class='blog-item'>
                <h3>{$p['title']}</h3>
                <p>By: {$p['author']} | Status: <strong>{$p['status']}</strong> | Date: {$p['created_at']}</p>
                <p>{$p['content']}</p>
                <div class='blog-actions'>
                    <a href='?approve={$p['id']}' class='approve'>✓ Approve</a>
                    <a href='?reject={$p['id']}' class='reject'>✗ Reject</a>
                    <a href='?delete={$p['id']}' class='delete' onclick='return confirm(\"Delete this post?\")'>🗑 Delete</a>
                </div>
                <hr>
              </div>";
    }
    ?>
</div>
</body>
</html>