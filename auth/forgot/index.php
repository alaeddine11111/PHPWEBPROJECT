<?php
// SUPER SIMPLE forgot-password page
// - User enters an email
// - If email exists, show a fixed reset link
// - No tokens, no saving anything, no expiry

session_start();

$error = '';
$info = '';
$resetLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } else {
        // Connect to DB
        require_once __DIR__ . '/../../includes/database.php';

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // No token, just direct them to the reset page
            $resetLink = "../reset/?email=" . urlencode($email);
            $info = "Reset link created:";
        } else {
            $info = "If that email exists, a reset link has been created.";
        }
    }
}

// Load the view
require_once __DIR__ . '/views/index.phtml';
?>
