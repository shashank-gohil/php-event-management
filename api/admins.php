<?php
require_once 'config.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// All endpoints require authentication
$currentUsername = requireAuth();
$admins = loadAdmins();

// GET all admin users
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id'])) {
    // Remove passwords from response for security
    $sanitizedAdmins = [];
    
    foreach ($admins as $admin) {
        $sanitizedAdmin = $admin;
        unset($sanitizedAdmin['password']);
        $sanitizedAdmins[] = $sanitizedAdmin;
    }
    
    apiResponse(true, $sanitizedAdmins, 'Admin users retrieved successfully');
}

// GET single admin user
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    foreach ($admins as $admin) {
        if ($admin['id'] == $id) {
            // Remove password from response for security
            $sanitizedAdmin = $admin;
            unset($sanitizedAdmin['password']);
            
            apiResponse(true, $sanitizedAdmin, 'Admin user retrieved successfully');
        }
    }
    
    apiResponse(false, null, 'Admin user not found', 404);
}

// CREATE new admin user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = getInputData();
    
    // Validate required fields
    if (!isset($data['username']) || !isset($data['password'])) {
        apiResponse(false, null, 'Username and password are required', 400);
    }
    
    $username = trim($data['username']);
    $password = $data['password'];
    
    // Check if username is empty
    if (empty($username)) {
        apiResponse(false, null, 'Username cannot be empty', 400);
    }
    
    // Check if password is empty
    if (empty($password)) {
        apiResponse(false, null, 'Password cannot be empty', 400);
    }
    
    // Check if username already exists
    foreach ($admins as $admin) {
        if (strtolower($admin['username']) === strtolower($username)) {
            apiResponse(false, null, 'Username already exists', 409);
        }
    }
    
    // Create new admin user with ID
    $maxId = 0;
    foreach ($admins as $admin) {
        if ($admin['id'] > $maxId) {
            $maxId = $admin['id'];
        }
    }
    
    $newAdmin = [
        'id' => $maxId + 1,
        'username' => $username,
        'password' => $password, // In production, would use password_hash()
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $admins[] = $newAdmin;
    
    if (saveAdmins($admins)) {
        // Remove password from response
        unset($newAdmin['password']);
        apiResponse(true, $newAdmin, 'Admin user created successfully', 201);
    } else {
        apiResponse(false, null, 'Failed to create admin user', 500);
    }
}

// UPDATE an admin user
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = getInputData();
    
    // Validate required fields
    if (!isset($data['username'])) {
        apiResponse(false, null, 'Username is required', 400);
    }
    
    $username = trim($data['username']);
    
    // Check if username is empty
    if (empty($username)) {
        apiResponse(false, null, 'Username cannot be empty', 400);
    }
    
    $found = false;
    $targetAdmin = null;
    
    // Find the admin to update
    foreach ($admins as $key => $admin) {
        if ($admin['id'] == $id) {
            $found = true;
            $targetAdmin = $admin;
            break;
        }
    }
    
    if (!$found) {
        apiResponse(false, null, 'Admin user not found', 404);
    }
    
    // Check if username already exists (if changing username)
    if ($targetAdmin['username'] !== $username) {
        foreach ($admins as $admin) {
            if (strtolower($admin['username']) === strtolower($username) && $admin['id'] != $id) {
                apiResponse(false, null, 'Username already exists', 409);
            }
        }
    }
    
    // Update the admin
    $updatedAdmin = [
        'id' => (int)$id,
        'username' => $username,
        'password' => isset($data['password']) && !empty($data['password']) ? $data['password'] : $targetAdmin['password'],
        'created_at' => $targetAdmin['created_at']
    ];
    
    $admins[$key] = $updatedAdmin;
    
    // If updating current user, update session
    if ($currentUsername === $targetAdmin['username']) {
        session_start();
        $_SESSION['admin_username'] = $username;
    }
    
    if (saveAdmins($admins)) {
        // Remove password from response
        unset($updatedAdmin['password']);
        apiResponse(true, $updatedAdmin, 'Admin user updated successfully');
    } else {
        apiResponse(false, null, 'Failed to update admin user', 500);
    }
}

// DELETE an admin user
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $found = false;
    $targetUsername = null;
    
    foreach ($admins as $key => $admin) {
        if ($admin['id'] == $id) {
            $found = true;
            $targetUsername = $admin['username'];
            
            // Prevent self-deletion
            if ($currentUsername === $targetUsername) {
                apiResponse(false, null, 'You cannot delete your own account', 403);
            }
            
            unset($admins[$key]);
            break;
        }
    }
    
    if (!$found) {
        apiResponse(false, null, 'Admin user not found', 404);
    }
    
    // Re-index array
    $admins = array_values($admins);
    
    if (saveAdmins($admins)) {
        apiResponse(true, null, 'Admin user deleted successfully');
    } else {
        apiResponse(false, null, 'Failed to delete admin user', 500);
    }
}

// If we got here, the endpoint doesn't exist
apiResponse(false, null, 'Invalid API endpoint', 404);
?> 