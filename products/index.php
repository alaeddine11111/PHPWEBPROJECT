<?php
// Start session and check login
session_start();
if (!isset($_SESSION['website_access']) || $_SESSION['website_access'] !== true) {
    header('Location: ../auth/access/');
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../auth/login/');
    exit;
}

// Include database and config
require_once '../includes/database.php';
require_once 'config.php';

// Load products and stats
$products = getProducts();
$stats = getStats();

// Include the layout template
require_once 'layout.phtml';