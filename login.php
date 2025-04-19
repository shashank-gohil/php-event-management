<?php
session_start();

// Initialize error message
$error_message = '';

// Check if user is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Load admin users from file
    $adminsFile = 'admins.json';
    if (file_exists($adminsFile)) {
        $admins = json_decode(file_get_contents($adminsFile), true);
        
        // Check if username and password match any admin
        $authenticated = false;
        foreach ($admins as $admin) {
            if ($username === $admin['username'] && $password === $admin['password']) {
                // Set session
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                
                // Redirect to admin panel
                header('Location: admin.php');
                exit;
            }
        }
        
        // If we got here, authentication failed
        $error_message = 'Invalid username or password';
    } else {
        // If admins file doesn't exist, use default admin
        if ($username === 'admin' && $password === 'password123') {
            // Set session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            
            // Redirect to admin panel
            header('Location: admin.php');
            exit;
        } else {
            $error_message = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #4CAF50, #2c3e50);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 2rem;
        }
        
        .form-icon {
            font-size: 50px;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #495057;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #ced4da;
            border-radius: 50px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-control:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.25);
        }

        .btn {
            display: inline-block;
            background: #4CAF50;
            color: #fff;
            padding: 15px 20px;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 500;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(76, 175, 80, 0.2);
        }

        .btn-primary {
            background: #4CAF50;
        }

        .btn-primary:hover {
            background: #3d8b40;
            box-shadow: 0 6px 8px rgba(76, 175, 80, 0.3);
            transform: translateY(-2px);
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: left;
        }
        
        .message::before {
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-right: 10px;
            font-size: 20px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .error::before {
            content: '\f071';
        }

        .home-link {
            text-align: center;
            margin-top: 30px;
        }

        .home-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .home-link a i {
            margin-right: 5px;
        }

        .home-link a:hover {
            color: #3d8b40;
        }
        
        @media (max-width: 500px) {
            .login-container {
                padding: 30px 20px;
                margin: 0 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <h1>Admin Login</h1>
        
        <?php if (!empty($error_message)): ?>
        <div class="message error">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>
        
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="home-link">
            <a href="index.php"><i class="fas fa-home"></i> Back to Events Page</a>
        </div>
    </div>
</body>
</html> 