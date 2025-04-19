<?php
require_once 'config.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Login endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['action'])) {
    // Get input data
    $data = getInputData();
    
    if (!isset($data['username']) || !isset($data['password'])) {
        apiResponse(false, null, 'Username and password are required', 400);
    }
    
    $username = trim($data['username']);
    $password = $data['password'];
    
    // Load admin users
    $admins = loadAdmins();
    
    // Check credentials
    foreach ($admins as $admin) {
        if ($admin['username'] === $username && $admin['password'] === $password) {
            // Start session
            session_start();
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            
            // Return success response
            apiResponse(true, [
                'username' => $username,
                'message' => 'Login successful'
            ], 'You have been logged in successfully');
        }
    }
    
    // If we got here, login failed
    apiResponse(false, null, 'Invalid username or password', 401);
}

// Logout endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_start();
    
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    apiResponse(true, null, 'You have been logged out successfully');
}

// Check auth status endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'status') {
    session_start();
    
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        apiResponse(true, [
            'authenticated' => true,
            'username' => $_SESSION['admin_username']
        ], 'User is authenticated');
    } else {
        apiResponse(true, [
            'authenticated' => false
        ], 'User is not authenticated');
    }
}

// If we got here, the endpoint doesn't exist
apiResponse(false, null, 'Invalid API endpoint', 404);
?> 