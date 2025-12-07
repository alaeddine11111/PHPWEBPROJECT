<?php
session_start();

// Check if user has secure access
if (!isset($_SESSION['secure_access']) || $_SESSION['secure_access'] !== true) {
    header('Location: secure.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockFlow - Secure Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #2c1810 0%, #1a0f0f 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        .secure-panel {
            max-width: 1200px;
            margin: 0 auto;
        }
        .panel-header {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .panel-header h1 {
            color: #fff;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
        }
        .panel-header .subtitle {
            color: #dc3545;
            font-weight: 600;
        }
        .danger-zone {
            background: rgba(220, 53, 69, 0.1);
            border: 2px solid rgba(220, 53, 69, 0.3);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .danger-zone h2 {
            color: #ff6b6b;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        .danger-action {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .danger-action h3 {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .danger-action p {
            color: #aaa;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .btn-danger-action {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: #fff;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-danger-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(220, 53, 69, 0.4);
            color: #fff;
            text-decoration: none;
        }
        .btn-exit {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            border: none;
            color: #fff;
            padding: 0.6rem 2rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            float: right;
        }
        .btn-exit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(108, 117, 125, 0.4);
            color: #fff;
            text-decoration: none;
        }
        .info-section {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .info-section h3 {
            color: #fff;
            margin-bottom: 1rem;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-item strong {
            color: #fff;
        }
        .info-item span {
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="secure-panel">
        <div class="panel-header">
            <div style="float: right;">
                <a href="secure-logout.php" class="btn btn-exit">
                    <i class="bi bi-box-arrow-right me-2"></i>Exit Panel
                </a>
            </div>
            <h1><i class="bi bi-shield-lock"></i> Secure Admin Panel</h1>
            <p class="subtitle">Restricted Access Zone</p>
        </div>

        <div style="clear: both;"></div>

        <!-- System Information -->
        <div class="info-section">
            <h3><i class="bi bi-info-circle me-2"></i>System Information</h3>
            <div class="info-item">
                <strong>Access Status:</strong>
                <span><i class="bi bi-check-circle text-success me-2"></i>Authorized</span>
            </div>
            <div class="info-item">
                <strong>Session ID:</strong>
                <span><?php echo substr(session_id(), 0, 10); ?>...</span>
            </div>
            <div class="info-item">
                <strong>Server Time:</strong>
                <span><?php echo date('Y-m-d H:i:s'); ?></span>
            </div>
            <div class="info-item">
                <strong>PHP Version:</strong>
                <span><?php echo phpversion(); ?></span>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="danger-zone">
            <h2><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h2>

            <div class="danger-action">
                <h3><i class="bi bi-trash me-2"></i>Clear Application Cache</h3>
                <p>Remove all cached data and temporary files. This may impact performance temporarily.</p>
                <button class="btn-danger-action" onclick="if(confirm('Clear cache?')) alert('Cache cleared (demo)')">
                    <i class="bi bi-trash me-2"></i>Clear Cache
                </button>
            </div>

            <div class="danger-action">
                <h3><i class="bi bi-arrow-clockwise me-2"></i>Reset Database</h3>
                <p>Reset all database records to default values. This action cannot be undone!</p>
                <button class="btn-danger-action" onclick="if(confirm('Reset database? This cannot be undone!')) alert('Database reset (demo)')">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Database
                </button>
            </div>

            <div class="danger-action">
                <h3><i class="bi bi-cloud-upload me-2"></i>Full System Backup</h3>
                <p>Create a complete backup of the entire system including database and files.</p>
                <button class="btn-danger-action" onclick="if(confirm('Create backup?')) alert('Backup created (demo)')">
                    <i class="bi bi-cloud-upload me-2"></i>Create Backup
                </button>
            </div>
        </div>

        <!-- Access Key Reference -->
        <div class="info-section">
            <h3><i class="bi bi-key me-2"></i>Security Notes</h3>
            <div class="info-item">
                <strong>Access Method:</strong>
                <span>Predefined Key Authentication</span>
            </div>
            <div class="info-item">
                <strong>Session Duration:</strong>
                <span>Until browser close or manual logout</span>
            </div>
            <div class="info-item">
                <strong>Last Access:</strong>
                <span><?php echo date('Y-m-d H:i:s'); ?></span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
