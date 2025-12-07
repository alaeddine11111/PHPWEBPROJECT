<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// If access key is already set in session, allow entry
if (isset($_SESSION['secure_access']) && $_SESSION['secure_access'] === true) {
    header('Location: secure-panel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockFlow - Secure Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .secure-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .secure-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            padding: 2rem;
        }
        .secure-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .secure-header h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .secure-header .subtitle {
            color: #aaa;
            font-size: 0.95rem;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            color: #fff;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .btn-unlock {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        .btn-unlock:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(220, 53, 69, 0.4);
            color: #fff;
        }
        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b6b;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            color: #fff;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }
        .lock-icon {
            font-size: 3rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        .back-link a {
            color: #0d6efd;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="secure-container">
        <div class="secure-card">
            <div class="secure-header">
                <div class="lock-icon">
                    <i class="bi bi-lock-fill"></i>
                </div>
                <h1>Secure Panel</h1>
                <p class="subtitle">Enter access key to continue</p>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] === '1'): ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle me-2"></i>Invalid access key
                </div>
            <?php endif; ?>

            <form method="POST" action="secure-check.php">
                <div class="form-group">
                    <label for="access_key">
                        <i class="bi bi-key me-2"></i>Access Key
                    </label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="access_key" 
                        name="access_key" 
                        placeholder="Enter the access key" 
                        required 
                        autofocus
                    >
                </div>

                <button type="submit" class="btn btn-unlock">
                    <i class="bi bi-unlock me-2"></i>Unlock
                </button>
            </form>

            <div class="back-link">
                <a href="login.php">Back to Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
