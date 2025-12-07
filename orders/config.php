<?php
// Database configuration
$host = "localhost";
$dbname = "gestion_stocke";
$username = "root";
$password = "";

// Create connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get all orders
function getOrders() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY date_order DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get order statistics
function getOrderStats() {
    global $pdo;
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN type = 'Incoming' THEN 1 ELSE 0 END) as incoming_orders,
        SUM(CASE WHEN type = 'Outgoing' THEN 1 ELSE 0 END) as outgoing_orders,
        SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as processing_orders,
        SUM(CASE WHEN status = 'Shipped' THEN 1 ELSE 0 END) as shipped_orders,
        SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) as delivered_orders,
        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_orders
    FROM orders");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new order
if ($_POST && isset($_POST['add_order'])) {
    $order_id = $_POST['order_id'];
    $type = $_POST['type'];
    $status = $_POST['status'];
    $date_order = $_POST['date_order'];
    $customer_name = $_POST['customer_name'];
    $items_count = $_POST['items_count'];
    
    $stmt = $pdo->prepare("INSERT INTO orders (order_id, type, status, date_order, customer_name, items_count) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$order_id, $type, $status, $date_order, $customer_name, $items_count]);
    
    header("Location: index.php?message=Order added successfully");
    exit;
}

// Delete order
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->execute([$_GET['delete_id']]);
    
    header("Location: index.php?message=Order deleted successfully");
    exit;
}

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->execute([$new_status, $order_id]);
    
    header("Location: index.php?message=Order status updated successfully");
    exit;
}
?>