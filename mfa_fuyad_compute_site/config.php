<?php
$host = 'sql302.infinityfree.com';
$user = 'if0_41570077';
$pass = 'Fuyad2026';
$db = 'if0_41570077_fuyad_computer';

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>