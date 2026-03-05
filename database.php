<?php
// Premium AI Chat - Database Configuration
$host = "localhost";
$user = "rsoa_rsoa278_5";
$password = "654321#";
$database = "rsoa_rsoa278_5";

// Create connection with error handling
try {
    $conn = new mysqli($host, $user, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF8mb4 for full Unicode support
    $conn->set_charset("utf8mb4");
    
    // Set timezone
    $conn->query("SET time_zone = '+00:00'");
    
} catch (Exception $e) {
    // Log error and show user-friendly message
    error_log("Database Error: " . $e->getMessage());
    die("⚠️ Database connection error. Please try again later.");
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Premium database helper functions
class PremiumDatabase {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Secure query execution
    public function executeQuery($sql, $params = [], $types = '') {
        try {
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }
            
            if (!empty($params)) {
                if (empty($types)) {
                    $types = str_repeat('s', count($params));
                }
                $stmt->bind_param($types, ...$params);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            return $stmt;
        } catch (Exception $e) {
            error_log("Query Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Fetch single row
    public function fetchSingle($sql, $params = [], $types = '') {
        $stmt = $this->executeQuery($sql, $params, $types);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
    
    // Fetch all rows
    public function fetchAll($sql, $params = [], $types = '') {
        $stmt = $this->executeQuery($sql, $params, $types);
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
    
    // Insert data and return ID
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);
        $types = str_repeat('s', count($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->executeQuery($sql, $values, $types);
        $insertId = $stmt->insert_id;
        $stmt->close();
        
        return $insertId;
    }
}

// Create database helper instance
$db = new PremiumDatabase($conn);

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current username
function getCurrentUsername() {
    return $_SESSION['username'] ?? 'Guest';
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Generate random session ID for chat
function generateSessionId() {
    return bin2hex(random_bytes(16));
}

// Format date for display
function formatDate($date) {
    return date('F j, Y \a\t g:i A', strtotime($date));
}

// Get user avatar initial
function getAvatarInitial($username) {
    return strtoupper(substr($username, 0, 1));
}

// Check if request is AJAX
function isAjaxRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// JSON response helper
function jsonResponse($success, $data = [], $error = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'error' => $error,
        'timestamp' => time()
    ]);
    exit();
}
?>
