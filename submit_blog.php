<?php
include 'config.php';
$stmt = $conn->prepare("INSERT INTO blog_posts (title, content, author) VALUES (?,?,?)");
$stmt->bind_param("sss", $_POST['title'], $_POST['content'], $_POST['author']);
$stmt->execute();
header("Location: blog.php?msg=submitted");
?>