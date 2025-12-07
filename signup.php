<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'All fields are required';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $password_confirm) {
        $error = 'Passwords do not match';
    } else {
        // Simple file-based storage (for demo; use database in production)
        $users_file = __DIR__ . '/includes/users.json';
        $users = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : [];

        // Check if username already exists
        $username_exists = false;
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $username_exists = true;
                break;
            }
        }

        if ($username_exists) {
            $error = 'Username already exists';
        } else {
            // Add new user
            $users[] = [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];

            // Save to file
            file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
            $success = 'Account created successfully! Redirecting to login...';
            
            // Redirect to login after 2 seconds
            echo '<meta http-equiv="refresh" content="2;url=login.php">';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockFlow - Sign Up</title>
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
        .signup-container {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
        }
        .signup-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            padding: 2rem;
        }
        .signup-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .signup-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .signup-header .subtitle {
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
        .btn-signup {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(40, 167, 69, 0.4);
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
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #51cf66;
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
        .login-link {
            text-align: center;
            margin-top: 2rem;
            color: #aaa;
        }
        .login-link a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .password-strength {
            font-size: 0.85rem;
            color: #aaa;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <h1><i class="bi bi-box-seam"></i> StockFlow</h1>
                <p class="subtitle">Create Your Account</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert-success">
                    <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
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
                        placeholder="Choose a username" 
                        required 
                        minlength="3"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="bi bi-envelope me-2"></i>Email
                    </label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your email" 
                        required
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
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
                        placeholder="Create a password" 
                        required
                        minlength="6"
                    >
                    <small class="password-strength">Minimum 6 characters</small>
                </div>

                <div class="form-group">
                    <label for="password_confirm">
                        <i class="bi bi-lock-fill me-2"></i>Confirm Password
                    </label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password_confirm" 
                        name="password_confirm" 
                        placeholder="Confirm your password" 
                        required
                        minlength="6"
                    >
                </div>

                <button type="submit" class="btn btn-signup">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
