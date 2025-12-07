<?php
// Déterminer la page active automatiquement
$current_file = $_SERVER['PHP_SELF'];
$current_dir = dirname($current_file);

// Convertir le chemin en nom de section
$active_section = 'dashboard'; // par défaut

if (strpos($current_dir, 'products') !== false) {
    $active_section = 'products';
} elseif (strpos($current_dir, 'orders') !== false) {
    $active_section = 'orders';
} elseif (strpos($current_dir, 'suppliers') !== false) {
    $active_section = 'suppliers';
} elseif (strpos($current_dir, 'reports') !== false) {
    $active_section = 'reports';
}
?>

<!-- Sidebar -->
<div class="sidebar p-3">
    <div class="sidebar-brand mb-4 pb-3 border-bottom border-secondary">
        <h4 class="text-white"><i class="bi bi-box-seam"></i> <span>StockFlow</span></h4>
        <small class="text-muted">Management System</small>
    </div>
    
    <div class="nav flex-column">
        <a href="../index.php" class="nav-link mb-2 <?php echo $active_section == 'dashboard' ? 'bg-primary text-white' : 'text-muted'; ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        <a href="../products/index.php" class="nav-link mb-2 <?php echo $active_section == 'products' ? 'bg-primary text-white' : 'text-muted'; ?>">
            <i class="bi bi-box"></i> <span>Product Inventory</span>
        </a>
        <a href="../orders/index.php" class="nav-link mb-2 <?php echo $active_section == 'orders' ? 'bg-primary text-white' : 'text-muted'; ?>">
            <i class="bi bi-cart"></i> <span>Orders</span>
        </a>
        <a href="../suppliers/index.php" class="nav-link mb-2 <?php echo $active_section == 'suppliers' ? 'bg-primary text-white' : 'text-muted'; ?>">
            <i class="bi bi-truck"></i> <span>Suppliers</span>
        </a>
        <a href="../reports/index.php" class="nav-link mb-2 <?php echo $active_section == 'reports' ? 'bg-primary text-white' : 'text-muted'; ?>">
            <i class="bi bi-graph-up"></i> <span>Reports</span>
        </a>
    </div>

    <!-- User section at bottom -->
    <div class="mt-5 pt-3 border-top border-secondary">
        <div class="text-white mb-2">
            <small class="text-muted">Logged in as</small><br>
            <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong>
        </div>
        <a href="../auth/logout/" class="btn btn-outline-danger btn-sm w-100">
            <i class="bi bi-box-arrow-right me-2"></i>Logout
        </a>
    </div>
</div>