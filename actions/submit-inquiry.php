<?php
session_start();

// Security headers
header("Content-Type: application/json");

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals((string)$_SESSION['csrf_token'], (string)$_POST['csrf_token'])) {
    http_response_code(403);
    die(json_encode(["success" => false, "message" => "Invalid security token. Please refresh the page and try again."]));
}

// Rate limiting (3 requests per minute)
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateLimitKey = "rate_limit_$ip";
$currentTime = time();

if (!isset($_SESSION[$rateLimitKey])) {
    $_SESSION[$rateLimitKey] = [];
}
$_SESSION[$rateLimitKey] = array_filter($_SESSION[$rateLimitKey], fn($timestamp) => ($currentTime - $timestamp) < 60);
if (count($_SESSION[$rateLimitKey]) >= 3) {
    http_response_code(429);
    die(json_encode(["success" => false, "message" => "Too many requests. Please wait before submitting again."]));
}

$_SESSION[$rateLimitKey][] = $currentTime;

// Database configuration (hardcoded)
$config = [
    'host' => 'localhost',
    'user' => 'u445711992_eternal_advent',
    'pass' => 'Done@2024',
    'name' => 'u445711992_admins_eternal'
];

// Input validation and sanitization
$name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8') : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8') : '';

$errors = [];
if (empty($name) || strlen($name) > 100) {
    $errors[] = "Name must be between 1-100 characters";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    $errors[] = "Invalid email format";
}
if (empty($message) || strlen($message) > 2000) {
    $errors[] = "Message must be between 1-2000 characters";
}

if (!empty($errors)) {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => implode(", ", $errors)]));
}

// Database connection using PDO
try {
    $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Parameterized query
    $stmt = $pdo->prepare("INSERT INTO inquiries (name, email, message) VALUES (:name, :email, :message)");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message
    ]);

    // Regenerate CSRF token after submission
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Response
    echo json_encode(["success" => true, "message" => "Inquiry submitted successfully"]);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An error occurred while processing your request"]);
}
