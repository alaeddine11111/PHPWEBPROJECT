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

// Get all suppliers
function getSuppliers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM inventories ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get supplier statistics
function getSupplierStats() {
    global $pdo;
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total_suppliers,
        SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_suppliers,
        SUM(CASE WHEN status = 'Inactive' THEN 1 ELSE 0 END) as inactive_suppliers,
        SUM(products_count) as total_products
    FROM inventories");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new supplier
if ($_POST && isset($_POST['add_supplier'])) {
    $supplier_name = $_POST['supplier_name'];
    $contact_person = $_POST['contact_person'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $products_count = $_POST['products_count'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("INSERT INTO inventories (supplier_name, contact_person, email, phone, products_count, status) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$supplier_name, $contact_person, $email, $phone, $products_count, $status]);
    
    header("Location: index.php?message=Supplier added successfully");
    exit;
}

// Delete supplier
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM inventories WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    
    header("Location: index.php?message=Supplier deleted successfully");
    exit;
}

// Update supplier status
if (isset($_POST['update_status'])) {
    $supplier_id = $_POST['supplier_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE inventories SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $supplier_id]);
    
    header("Location: index.php?message=Supplier status updated successfully");
    exit;
}
?>