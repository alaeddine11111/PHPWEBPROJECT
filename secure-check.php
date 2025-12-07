<?php
session_start();

// Predefined access key (stored in JavaScript for client-side reference)
// Server-side validation uses the same key
define('SECURE_KEY', 'AdminKey2024');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $access_key = $_POST['access_key'] ?? '';

    if ($access_key === SECURE_KEY) {
        $_SESSION['secure_access'] = true;
        header('Location: secure-panel.php');
        exit;
    } else {
        header('Location: secure.php?error=1');
        exit;
    }
}

// If not POST, redirect to secure page
header('Location: secure.php');
exit;
