<?php

// Get all products
function getProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products ORDER BY last_updates DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get single product by ID
function getProductById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get statistics
function getStats() {
    global $pdo;
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total_products,
        SUM(CASE WHEN quantity > 0 THEN 1 ELSE 0 END) as items_in_stock,
        SUM(CASE WHEN quantity <= 10 AND quantity > 0 THEN 1 ELSE 0 END) as low_stock_alerts
    FROM products");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new product
if ($_POST && isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $image = $_POST['product_image'];
    
    // Determine status
    if ($quantity == 0) {
        $status = 'out of stock';
    } elseif ($quantity <= 10) {
        $status = 'low stock';
    } else {
        $status = 'in stock';
    }
    
    $stmt = $pdo->prepare("INSERT INTO products (product_name, category, quantity, location, status, last_updates, product_image) 
                          VALUES (?, ?, ?, ?, ?, CURDATE(), ?)");
    $stmt->execute([$name, $category, $quantity, $location, $status, $image]);
    
    header("Location: index.php?message=Product added successfully");
    exit;
}

// Update product
if ($_POST && isset($_POST['update_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $image = $_POST['product_image'];
    
    // Determine status
    if ($quantity == 0) {
        $status = 'out of stock';
    } elseif ($quantity <= 10) {
        $status = 'low stock';
    } else {
        $status = 'in stock';
    }
    
    $stmt = $pdo->prepare("UPDATE products 
                          SET product_name = ?, category = ?, quantity = ?, location = ?, status = ?, last_updates = CURDATE(), product_image = ?
                          WHERE product_id = ?");
    $stmt->execute([$name, $category, $quantity, $location, $status, $image, $id]);
    
    header("Location: index.php?message=Product updated successfully");
    exit;
}

// Delete product
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$_GET['delete_id']]);
    
    header("Location: index.php?message=Product deleted successfully");
    exit;
}

// Get products and stats
$products = getProducts();
$stats = getStats();
?>