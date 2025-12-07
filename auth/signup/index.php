<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: ../../index.php');
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
        // Use database-backed users table
        try {
            require_once __DIR__ . '/../../includes/database.php';

            // Check if username or email already exists
            $check = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1');
            $check->execute([':username' => $username, ':email' => $email]);
            $exists = $check->fetch(PDO::FETCH_ASSOC);

            if ($exists) {
                $error = 'Username or email already exists';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
                $insert->execute([':username' => $username, ':email' => $email, ':password' => $hash]);

                // Redirect to login after successful registration
                header('Location: ../access/');
                exit;
            }
        } catch (Exception $e) {
            // Surface database errors to help debugging — change/remove in production
            $error = 'Registration error: ' . $e->getMessage();
        }
    }
}

// Load the view
require_once __DIR__ . '/views/index.phtml';
?>
