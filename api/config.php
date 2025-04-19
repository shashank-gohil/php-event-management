<?php
// API Configuration

// Set content type to JSON for all API responses
header('Content-Type: application/json');

// Allow cross-origin requests (if needed)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// API response function
function apiResponse($success, $data = null, $message = '', $status_code = 200) {
    http_response_code($status_code);
    
    $response = [
        'success' => $success,
        'message' => $message,
        'data' => $data
    ];
    
    echo json_encode($response);
    exit;
}

// API authentication check
function requireAuth() {
    session_start();
    
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        apiResponse(false, null, 'Unauthorized access', 401);
    }
    
    return $_SESSION['admin_username'];
}

// Load events from file
function loadEvents() {
    $eventsFile = '../events.json';
    
    if (file_exists($eventsFile)) {
        return json_decode(file_get_contents($eventsFile), true);
    }
    
    return [];
}

// Save events to file
function saveEvents($events) {
    $eventsFile = '../events.json';
    return file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));
}

// Load admins from file
function loadAdmins() {
    $adminsFile = '../admins.json';
    
    if (file_exists($adminsFile)) {
        return json_decode(file_get_contents($adminsFile), true);
    }
    
    return [
        [
            'id' => 1,
            'username' => 'admin',
            'password' => 'password123',
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
}

// Save admins to file
function saveAdmins($admins) {
    $adminsFile = '../admins.json';
    return file_put_contents($adminsFile, json_encode($admins, JSON_PRETTY_PRINT));
}

// Get JSON input data
function getInputData() {
    return json_decode(file_get_contents('php://input'), true);
}
?> 