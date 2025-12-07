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

// Load suppliers and stats
$suppliers = getSuppliers();
$stats = getSupplierStats();

// Include the layout template
require_once 'layout.phtml';