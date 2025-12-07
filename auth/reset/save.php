<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$email || !$password) {
    die("Missing data.");
}

// Connect to database
require_once __DIR__ . '/../../includes/database.php';

// Hash password (simple secure version)
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Update user password
$stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email LIMIT 1");
$stmt->execute([
    ':password' => $hashed,
    ':email' => $email
]);

// Check if update worked
if ($stmt->rowCount() > 0) {
    $success = true;
} else {
    $success = false;
}

// Load the view
require_once __DIR__ . '/views/save.phtml';
?>
