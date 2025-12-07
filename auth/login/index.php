<?php
session_start();

// Check if user has accessed the website via password gate
if (!isset($_SESSION['website_access']) || $_SESSION['website_access'] !== true) {
    header('Location: ../access/');
    exit;
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: ../../index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // Use database users table for authentication
    try {
        require_once __DIR__ . '/../../includes/database.php';

        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $authenticated = false;
        if ($user) {
            if (isset($user['password']) && password_verify($password, $user['password'])) {
                $authenticated = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
            }
        }

        // No demo fallback — require real users in the database

        if ($authenticated) {
            $_SESSION['logged_in'] = true;
            header('Location: ../../index.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } catch (Exception $e) {
        $error = 'Authentication error';
    }
}

// Load the view
require_once __DIR__ . '/views/index.phtml';
?>
