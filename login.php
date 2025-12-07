<?php
session_start();

// Check if user has accessed the website via password gate
if (!isset($_SESSION['website_access']) || $_SESSION['website_access'] !== true) {
    header('Location: access.php');
    exit;
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check against registered users file
    $users_file = __DIR__ . '/includes/users.json';
    $users = [];
    
    if (file_exists($users_file)) {
        $users = json_decode(file_get_contents($users_file), true) ?? [];
    }

    // Also check hardcoded demo account
    $demo_users = [
        ['username' => 'admin', 'password' => 'password123']
    ];
    $all_users = array_merge($users, $demo_users);

    $authenticated = false;
    foreach ($all_users as $user) {
        if ($user['username'] === $username) {
            // For demo account, plain text comparison; for registered users, hash verification
            if (isset($user['password']) && is_string($user['password'])) {
                if (password_verify($password, $user['password']) || $user['password'] === $password) {
                    $authenticated = true;
                    break;
                }
            }
        }
    }

    if ($authenticated) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockFlow - Login</title>
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
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            padding: 2rem;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .login-header .subtitle {
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
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            color: #fff;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(13, 110, 253, 0.4);
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
        .credentials-hint {
            background: rgba(255, 255, 255, 0.05);
            border-left: 3px solid #0d6efd;
            padding: 1rem;
            border-radius: 6px;
            margin-top: 2rem;
            font-size: 0.85rem;
            color: #ccc;
        }
        .credentials-hint strong {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="bi bi-box-seam"></i> StockFlow</h1>
                <p class="subtitle">Inventory Management System</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">
                        <i class="bi bi-person me-2"></i>Username
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="username" 
                        name="username" 
                        placeholder="Enter your username" 
                        required 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="bi bi-lock me-2"></i>Password
                    </label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        required
                    >
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>

            <div class="credentials-hint">
                <strong>Demo Credentials:</strong><br>
                Username: <code>admin</code><br>
                Password: <code>password123</code><br>
                <small style="color: #999; margin-top: 0.5rem; display: block;">Or <a href="signup.php" style="color: #0d6efd;">create a new account</a></small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
