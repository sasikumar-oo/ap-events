<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'business_cms');

// Site URL (update this with your actual domain)
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/admin');

define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim(htmlspecialchars($data)));
}

function getSetting($key, $default = '') {
    global $conn;
    $key = $conn->real_escape_string($key);
    $result = $conn->query("SELECT setting_value FROM settings WHERE setting_key = '$key'");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['setting_value'];
    }
    return $default;
}

function updateSetting($key, $value) {
    global $conn;
    $key = $conn->real_escape_string($key);
    $value = $conn->real_escape_string($value);
    $sql = "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value') 
            ON DUPLICATE KEY UPDATE setting_value = '$value'";
    return $conn->query($sql);
}

function uploadFile($file, $target_dir, $allowed_types = ['jpg', 'jpeg', 'png', 'gif']) {
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if file already exists
    $counter = 1;
    $original_name = pathinfo($file["name"], PATHINFO_FILENAME);
    while (file_exists($target_file)) {
        $target_file = $target_dir . $original_name . '_' . $counter . '.' . $imageFileType;
        $counter++;
    }
    
    // Check file size (5MB max)
    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => 'Sorry, your file is too large. Maximum size is 5MB.'];
    }
    
    // Allow certain file formats
    if (!in_array($imageFileType, $allowed_types)) {
        $formats = implode(', ', $allowed_types);
        return ['success' => false, 'message' => "Sorry, only $formats files are allowed."];
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return [
            'success' => true, 
            'file_path' => str_replace(dirname(__DIR__), '', $target_file),
            'file_name' => basename($target_file)
        ];
    } else {
        return ['success' => false, 'message' => 'Sorry, there was an error uploading your file.'];
    }
}

function deleteFile($file_path) {
    $full_path = dirname(__DIR__) . $file_path;
    if (file_exists($full_path) && is_file($full_path)) {
        return unlink($full_path);
    }
    return false;
}

// Check if user is logged in for protected pages
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect('login.php');
    }
}

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token() {
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Initialize settings array
$settings = [];
$result = $conn->query("SELECT setting_key, setting_value FROM settings");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}
?>
