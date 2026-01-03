<?php
// admin/config.php

// Replace line 3 in db.php with:
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Your existing database connection code below...

// Database configuration for XAMPP
define('DB_HOST', 'localhost:3308');
define('DB_NAME', 'hrms');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper functions
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin');
}

function isHR() {
    return isset($_SESSION['role']) && ($_SESSION['role'] == 'hr' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin');
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function formatDate($date) {
    if (empty($date) || $date == '0000-00-00') return 'N/A';
    return date('d-m-Y', strtotime($date));
}

function formatDateTime($datetime) {
    if (empty($datetime) || $datetime == '0000-00-00 00:00:00') return 'N/A';
    return date('d-m-Y H:i:s', strtotime($datetime));
}

function formatTime($time) {
    if (empty($time) || $time == '00:00:00') return '--:--';
    return date('h:i A', strtotime($time));
}

function generateEmployeeId() {
    global $conn;
    $year = date('y');
    $sql = "SELECT MAX(CAST(SUBSTRING(employee_id, 4) AS UNSIGNED)) as max_id 
            FROM users 
            WHERE employee_id LIKE 'EMP%'";
    $result = $conn->query($sql);
    $max_id = $result->fetch_assoc()['max_id'] ?? 0;
    $next_id = $max_id + 1;
    return 'EMP' . str_pad($next_id, 4, '0', STR_PAD_LEFT);
}

function getSetting($key) {
    global $conn;
    $sql = "SELECT setting_value FROM settings WHERE setting_key = '$key'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['setting_value'];
    }
    return null;
}

// Auto-create admin user if not exists
function createDefaultAdmin() {
    global $conn;
    
    $check_sql = "SELECT * FROM users WHERE email = 'admin@dayflow.com'";
    $result = $conn->query($check_sql);
    
    if ($result->num_rows == 0) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $emp_id = generateEmployeeId();
        
        // Create user
        $user_sql = "INSERT INTO users (employee_id, email, password, role, is_verified) 
                    VALUES ('$emp_id', 'admin@dayflow.com', '$password', 'admin', 1)";
        
        if ($conn->query($user_sql)) {
            $user_id = $conn->insert_id;
            
            // Create employee record
            $emp_sql = "INSERT INTO employees (user_id, first_name, last_name, joining_date, employment_status, department, position) 
                       VALUES ('$user_id', 'System', 'Administrator', CURDATE(), 'active', 'Administration', 'System Admin')";
            $conn->query($emp_sql);
            
            error_log("Default admin user created successfully");
        }
    }
}

// Call this function on config load
createDefaultAdmin();
?>