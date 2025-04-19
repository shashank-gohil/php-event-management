<?php
// Start session 
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

// Initialize or load admin users from file
$adminsFile = 'admins.json';

if (!file_exists($adminsFile)) {
    // Default admin if file doesn't exist
    $admins = [
        [
            'id' => 1,
            'username' => 'admin',
            'password' => 'password123', // In production, use password_hash()
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
    
    // Save default admin to file
    file_put_contents($adminsFile, json_encode($admins, JSON_PRETTY_PRINT));
} else {
    // Load admins from file
    $admins = json_decode(file_get_contents($adminsFile), true);
}

// Initialize variables
$id = '';
$username = '';
$password = '';
$isEditing = false;

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        // Create or update admin
        if ($_POST['action'] == 'save') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            
            // Validate input
            $errors = [];
            
            // Check if username is empty
            if (empty($username)) {
                $errors[] = "Username is required";
            }
            
            // Check if username already exists (only for new users)
            if (!isset($_POST['id']) || empty($_POST['id'])) {
                foreach ($admins as $admin) {
                    if (strtolower($admin['username']) === strtolower($username)) {
                        $errors[] = "Username already exists";
                        break;
                    }
                }
            }
            
            // Check if password is empty
            if (empty($password)) {
                $errors[] = "Password is required";
            }
            
            // If no errors, proceed with saving
            if (empty($errors)) {
                $newAdmin = [
                    'username' => $username,
                    'password' => $password, // In production, use password_hash()
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                // If editing, update existing admin
                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $id = $_POST['id'];
                    foreach ($admins as $key => $admin) {
                        if ($admin['id'] == $id) {
                            $newAdmin['id'] = $id;
                            $admins[$key] = $newAdmin;
                            break;
                        }
                    }
                } else {
                    // Create new admin with new ID
                    $maxId = 0;
                    foreach ($admins as $admin) {
                        if ($admin['id'] > $maxId) {
                            $maxId = $admin['id'];
                        }
                    }
                    $newAdmin['id'] = $maxId + 1;
                    $admins[] = $newAdmin;
                }
                
                // Save changes to file
                file_put_contents($adminsFile, json_encode($admins, JSON_PRETTY_PRINT));
                header("Location: admin_users.php?success=Admin user saved successfully");
                exit;
            }
        }
        
        // Delete admin
        if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
            $id = $_POST['id'];
            
            // Prevent deleting the logged-in user
            if ($_SESSION['admin_username'] !== $admins[array_search($id, array_column($admins, 'id'))]['username']) {
                foreach ($admins as $key => $admin) {
                    if ($admin['id'] == $id) {
                        unset($admins[$key]);
                        break;
                    }
                }
                // Re-index array
                $admins = array_values($admins);
                // Save changes to file
                file_put_contents($adminsFile, json_encode($admins, JSON_PRETTY_PRINT));
                header("Location: admin_users.php?success=Admin user deleted successfully");
                exit;
            } else {
                $errors = ["You cannot delete your own account"];
            }
        }
    }
}

// Handle edit requests
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $id = $_GET['edit'];
    foreach ($admins as $admin) {
        if ($admin['id'] == $id) {
            $username = $admin['username'];
            $password = $admin['password'];
            $isEditing = true;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 2.2rem;
        }
        
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .admin-panel {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .users-table th, .users-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .users-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .users-table tr:last-child td {
            border-bottom: none;
        }
        
        .users-table tr:hover {
            background-color: #f8f9fa;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 500;
            border: none;
            cursor: pointer;
            font-size: 14px;
            color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: #4CAF50;
            box-shadow: 0 4px 6px rgba(76, 175, 80, 0.2);
        }

        .btn-primary:hover {
            background: #3d8b40;
            box-shadow: 0 6px 8px rgba(76, 175, 80, 0.3);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #e74c3c;
            box-shadow: 0 4px 6px rgba(231, 76, 60, 0.2);
        }

        .btn-danger:hover {
            background: #c0392b;
            box-shadow: 0 6px 8px rgba(231, 76, 60, 0.3);
            transform: translateY(-2px);
        }
        
        .btn-danger[disabled] {
            background: #e74c3c88;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-warning {
            background: #f39c12;
            box-shadow: 0 4px 6px rgba(243, 156, 18, 0.2);
        }

        .btn-warning:hover {
            background: #d35400;
            box-shadow: 0 6px 8px rgba(243, 156, 18, 0.3);
            transform: translateY(-2px);
        }

        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #495057;
        }
        
        .form-group small {
            color: #6c757d;
            margin-top: 5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.25);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
            align-items: center;
            flex-wrap: nowrap;
        }
        
        .action-buttons form {
            margin: 0;
            padding: 0;
            flex: 0 0 auto;
        }
        
        .action-buttons .btn {
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-width: 100px;
        }
        
        .action-buttons .btn i {
            font-size: 14px;
        }
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .welcome-text {
            font-weight: 500;
            color: #6c757d;
        }
        
        .welcome-text strong {
            color: #2c3e50;
        }
        
        .nav-links {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #dc3545;
            display: flex;
            align-items: center;
        }
        
        .error:before {
            content: '\f071';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-right: 10px;
            font-size: 20px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
            display: flex;
            align-items: center;
        }
        
        .success:before {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-right: 10px;
            font-size: 20px;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .form-actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-width: 150px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .header-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .users-table {
                display: block;
                overflow-x: auto;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }
            
            .action-buttons .btn,
            .action-buttons form {
                width: 100%;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h1>Admin Users Management</h1>
            <div class="user-info">
                <span class="welcome-text">Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
                <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <div class="nav-links">
            <a href="admin.php" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> Manage Events</a>
            <a href="admin_users.php" class="btn btn-primary"><i class="fas fa-users"></i> Manage Admin Users</a>
        </div>

        <?php
        // Display success message
        if (isset($_GET['success'])) {
            echo '<div class="success">' . htmlspecialchars($_GET['success']) . '</div>';
        }
        
        // Display errors
        if (!empty($errors)) {
            echo '<div class="error">';
            foreach ($errors as $error) {
                echo htmlspecialchars($error) . '<br>';
            }
            echo '</div>';
        }
        ?>

        <!-- Add/Edit Admin Form -->
        <div class="admin-panel">
            <h2><?php echo $isEditing ? '<i class="fas fa-edit"></i> Edit Admin User' : '<i class="fas fa-user-plus"></i> Add New Admin User'; ?></h2>
            <form method="post" action="admin_users.php">
                <input type="hidden" name="action" value="save">
                <?php if ($isEditing): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username:</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password:</label>
                    <input type="password" id="password" name="password" class="form-control" <?php echo $isEditing ? '' : 'required'; ?> placeholder="<?php echo $isEditing ? 'Leave empty to keep current password' : 'Enter password'; ?>">
                    <?php if ($isEditing): ?>
                    <small>Leave empty to keep current password</small>
                    <?php endif; ?>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $isEditing ? '<i class="fas fa-save"></i> Update Admin User' : '<i class="fas fa-plus"></i> Add Admin User'; ?>
                    </button>
                    <?php if ($isEditing): ?>
                    <a href="admin_users.php" class="btn btn-warning"><i class="fas fa-times"></i> Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Admin Users Table -->
        <div class="admin-panel">
            <h2><i class="fas fa-users-cog"></i> Manage Admin Users</h2>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($admin['id']); ?></td>
                        <td>
                            <?php if ($_SESSION['admin_username'] === $admin['username']): ?>
                            <strong><?php echo htmlspecialchars($admin['username']); ?></strong> <span class="welcome-text">(you)</span>
                            <?php else: ?>
                            <?php echo htmlspecialchars($admin['username']); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo isset($admin['created_at']) ? htmlspecialchars($admin['created_at']) : 'N/A'; ?></td>
                        <td class="action-buttons">
                            <a href="admin_users.php?edit=<?php echo $admin['id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                            <?php if ($_SESSION['admin_username'] !== $admin['username']): ?>
                            <form method="post" action="admin_users.php" onsubmit="return confirm('Are you sure you want to delete this admin user?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                            <?php else: ?>
                            <button class="btn btn-danger" disabled title="You cannot delete your own account"><i class="fas fa-trash"></i> Delete</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html> 