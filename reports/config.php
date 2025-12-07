<?php
// Database configuration
$host = "127.0.0.1:3306";
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

// Get comprehensive reports data
function getReportsData() {
    global $pdo;
    
    $data = [];
    
    // Products statistics
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total_products,
        SUM(CASE WHEN quantity > 0 THEN 1 ELSE 0 END) as in_stock_products,
        SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) as out_of_stock_products,
        SUM(CASE WHEN quantity <= 10 AND quantity > 0 THEN 1 ELSE 0 END) as low_stock_products,
        SUM(quantity) as total_quantity,
        AVG(quantity) as avg_quantity
    FROM products");
    $data['products'] = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Orders statistics
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN type = 'Incoming' THEN 1 ELSE 0 END) as incoming_orders,
        SUM(CASE WHEN type = 'Outgoing' THEN 1 ELSE 0 END) as outgoing_orders,
        SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as processing_orders,
        SUM(CASE WHEN status = 'Shipped' THEN 1 ELSE 0 END) as shipped_orders,
        SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) as delivered_orders,
        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
        SUM(items_count) as total_items_ordered
    FROM orders");
    $data['orders'] = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Suppliers statistics
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total_suppliers,
        SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_suppliers,
        SUM(CASE WHEN status = 'Inactive' THEN 1 ELSE 0 END) as inactive_suppliers,
        SUM(products_count) as total_supplied_products
    FROM inventories");
    $data['suppliers'] = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Monthly orders data (for charts)
    $stmt = $pdo->query("SELECT 
        DATE_FORMAT(date_order, '%Y-%m') as month,
        COUNT(*) as order_count,
        SUM(items_count) as items_count
    FROM orders 
    WHERE date_order >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(date_order, '%Y-%m')
    ORDER BY month DESC
    LIMIT 6");
    $data['monthly_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Products by category
    $stmt = $pdo->query("SELECT 
        category,
        COUNT(*) as product_count,
        SUM(quantity) as total_quantity
    FROM products 
    GROUP BY category
    ORDER BY product_count DESC");
    $data['products_by_category'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Top suppliers by products
    $stmt = $pdo->query("SELECT 
        supplier_name,
        products_count,
        status
    FROM inventories 
    ORDER BY products_count DESC
    LIMIT 5");
    $data['top_suppliers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent stock movements
    $stmt = $pdo->query("SELECT 
        product_name,
        quantity,
        status,
        last_updates
    FROM products 
    ORDER BY last_updates DESC
    LIMIT 10");
    $data['recent_stock'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $data;
}

// Generate PDF report (placeholder function)
function generatePDFReport($type) {
    // This would generate a PDF in a real application
    // For now, we'll just return a success message
    return "PDF report generated successfully for " . $type;
}

// Export data to CSV
if (isset($_GET['export_csv'])) {
    $data = getReportsData();
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="inventory_report_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Write headers
    fputcsv($output, ['Report Type', 'Metric', 'Value']);
    
    // Products data
    fputcsv($output, ['Products', 'Total Products', $data['products']['total_products']]);
    fputcsv($output, ['Products', 'In Stock', $data['products']['in_stock_products']]);
    fputcsv($output, ['Products', 'Out of Stock', $data['products']['out_of_stock_products']]);
    fputcsv($output, ['Products', 'Low Stock', $data['products']['low_stock_products']]);
    
    // Orders data
    fputcsv($output, ['Orders', 'Total Orders', $data['orders']['total_orders']]);
    fputcsv($output, ['Orders', 'Incoming Orders', $data['orders']['incoming_orders']]);
    fputcsv($output, ['Orders', 'Outgoing Orders', $data['orders']['outgoing_orders']]);
    
    // Suppliers data
    fputcsv($output, ['Suppliers', 'Total Suppliers', $data['suppliers']['total_suppliers']]);
    fputcsv($output, ['Suppliers', 'Active Suppliers', $data['suppliers']['active_suppliers']]);
    
    fclose($output);
    exit;
}

// Generate PDF report
if (isset($_GET['generate_pdf'])) {
    $type = $_GET['generate_pdf'];
    $message = generatePDFReport($type);
    header("Location: index.php?message=" . urlencode($message));
    exit;
}
?>