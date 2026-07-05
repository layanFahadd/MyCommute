<?php

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

$host = "localhost";
$username = "root";
$password = "1213141516";
$database = "mycommute_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    exit("The application is temporarily unavailable.");
}

$conn->set_charset("utf8mb4");

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function set_flash($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type === 'success' ? 'success' : 'error',
        'message' => $message,
    ];
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token)
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function require_login($loginPath = 'login.php')
{
    if (empty($_SESSION['user_id'])) {
        set_flash('error', 'Please log in to continue.');
        header("Location: {$loginPath}");
        exit();
    }
}

function require_role($role)
{
    if (($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403);
        exit('Access Denied');
    }
}
