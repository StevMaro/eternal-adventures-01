<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection
$host = 'localhost';
$user = 'u445711992_eternal_advent';
$password = 'Done@2024';
$database = 'u445711992_admins_eternal';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Input validation and sanitization
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

$destination = sanitizeInput($_POST['destination'] ?? '');
$tripDates = sanitizeInput($_POST['tripDates'] ?? '');
$budget = sanitizeInput($_POST['budget'] ?? '');
$notes = sanitizeInput($_POST['notes'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');

// Validate inputs
if (empty($destination) || strlen($destination) > 100) {
    echo json_encode(['error' => 'Invalid destination']);
    exit;
}

if (empty($tripDates) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tripDates)) {
    echo json_encode(['error' => 'Invalid trip dates']);
    exit;
}

if (empty($budget) || !is_numeric($budget)) {
    echo json_encode(['error' => 'Invalid budget']);
    exit;
}

if (strlen($notes) > 500) {
    echo json_encode(['error' => 'Notes exceed maximum length']);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email']);
    exit;
}

// Insert data into the database
$stmt = $conn->prepare("INSERT INTO trips (destination, trip_dates, budget, notes, email) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $destination, $tripDates, $budget, $notes, $email);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Trip customization submitted successfully Our Team Will Contact You!']);
} else {
    echo json_encode(['error' => 'Failed to submit customization']);
}

$stmt->close();
$conn->close();
?>