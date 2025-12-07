<?php
session_start();

// If website access is already granted, redirect to login
if (isset($_SESSION['website_access']) && $_SESSION['website_access'] === true) {
    header('Location: ../login/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $website_password = $_POST['website_password'] ?? '';
    $master_key = 'website2024'; // Master password to enter the website
    
    if ($website_password === $master_key) {
        $_SESSION['website_access'] = true;
        header('Location: ../login/?redirect=true');
        exit;
    } else {
        $error = 'Invalid website password';
    }
}

// Load the view
require_once __DIR__ . '/views/index.phtml';
?>
