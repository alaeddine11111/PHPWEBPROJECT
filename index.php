<?php
// Start session and check login
session_start();

// Check website access first
if (!isset($_SESSION['website_access']) || $_SESSION['website_access'] !== true) {
    header('Location: auth/access/');
    exit;
}

// Then check user login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: auth/login/');
    exit;
}

// Include database connection
require_once __DIR__ . '/includes/database.php';

// Get dashboard statistics
function getDashboardStats($pdo) {
    // Products stats
    $products_stmt = $pdo->query("SELECT 
        COUNT(*) as total_products,
        SUM(CASE WHEN quantity > 0 THEN 1 ELSE 0 END) as in_stock,
        SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) as out_of_stock,
        SUM(CASE WHEN quantity <= 10 AND quantity > 0 THEN 1 ELSE 0 END) as low_stock
    FROM products");
    $products_stats = $products_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Orders stats
    $orders_stmt = $pdo->query("SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as processing_orders,
        SUM(CASE WHEN status = 'Shipped' THEN 1 ELSE 0 END) as shipped_orders
    FROM orders");
    $orders_stats = $orders_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Suppliers stats
    $suppliers_stmt = $pdo->query("SELECT 
        COUNT(*) as total_suppliers,
        SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_suppliers
    FROM inventories");
    $suppliers_stats = $suppliers_stmt->fetch(PDO::FETCH_ASSOC);
    
    return [
        'products' => $products_stats,
        'orders' => $orders_stats,
        'suppliers' => $suppliers_stats
    ];
}

// Get recent products
function getRecentProducts($pdo, $limit = 5) {
    $stmt = $pdo->query("SELECT product_name, last_updates FROM products ORDER BY last_updates DESC LIMIT $limit");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get statistics and recent data
try {
    $stats = getDashboardStats($pdo);
    $recent_products = getRecentProducts($pdo);
} catch(Exception $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Include the layout template
require_once __DIR__ . '/layout.phtml';
?>
