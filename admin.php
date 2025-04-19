<?php
// Start session 
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Admin Panel</title>
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

        .events-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .events-table th, .events-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .events-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .events-table tr:last-child td {
            border-bottom: none;
        }
        
        .events-table tr:hover {
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

        textarea.form-control {
            height: 120px;
            resize: vertical;
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
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
            display: flex;
            align-items: center;
        }
        
        .success-message:before {
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
            
            .events-table {
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
            
            .btn {
                width: 100%;
                text-align: center;
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
            <h1>Events Admin Panel</h1>
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
        // Initialize events from file or create if not exists
        $eventsFile = 'events.json';
        
        if (!file_exists($eventsFile)) {
            // Default events if file doesn't exist
            $events = [
                [
                    'id' => 1,
                    'name' => 'Team Meeting',
                    'date' => 'Monday, April 24, 2023 - 10:00 AM',
                    'description' => 'Weekly team sync to discuss project progress and upcoming milestones. All team members are required to attend.',
                    'link' => 'https://meet.google.com/abc-defg-hij'
                ],
                [
                    'id' => 2,
                    'name' => 'Training Session',
                    'date' => 'Wednesday, April 26, 2023 - 2:00 PM',
                    'description' => 'PHP Development best practices training session hosted by the senior development team. Open to all developers.',
                    'link' => 'https://meet.google.com/klm-nopq-rst'
                ],
                [
                    'id' => 3,
                    'name' => 'Client Presentation',
                    'date' => 'Friday, April 28, 2023 - 11:00 AM',
                    'description' => 'Presentation of new features to the client. Project managers and lead developers should attend.',
                    'link' => 'https://meet.google.com/uvw-xyz-123'
                ],
                [
                    'id' => 4,
                    'name' => 'Code Review Session',
                    'date' => 'Thursday, April 27, 2023 - 3:30 PM',
                    'description' => 'Team code review for the new feature implementation. Backend team members should prepare their code for review.',
                    'link' => 'https://meet.google.com/def-456-ghi'
                ]
            ];
            
            // Save default events to file
            file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));
        } else {
            // Load events from file
            $events = json_decode(file_get_contents($eventsFile), true);
        }

        // Initialize variables
        $id = '';
        $name = '';
        $date = '';
        $description = '';
        $link = '';
        $isEditing = false;

        // Handle form submissions
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['action'])) {
                // Create or update event
                if ($_POST['action'] == 'save') {
                    $newEvent = [
                        'name' => $_POST['name'],
                        'date' => $_POST['date'],
                        'description' => $_POST['description'],
                        'link' => $_POST['link']
                    ];

                    // If editing, update existing event
                    if (isset($_POST['id']) && !empty($_POST['id'])) {
                        $id = $_POST['id'];
                        foreach ($events as $key => $event) {
                            if ($event['id'] == $id) {
                                $newEvent['id'] = $id;
                                $events[$key] = $newEvent;
                                break;
                            }
                        }
                    } else {
                        // Create new event with new ID
                        $maxId = 0;
                        foreach ($events as $event) {
                            if ($event['id'] > $maxId) {
                                $maxId = $event['id'];
                            }
                        }
                        $newEvent['id'] = $maxId + 1;
                        $events[] = $newEvent;
                    }

                    // Save changes to file
                    file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));
                    header("Location: admin.php?success=Event saved successfully");
                    exit;
                }

                // Delete event
                if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
                    $id = $_POST['id'];
                    foreach ($events as $key => $event) {
                        if ($event['id'] == $id) {
                            unset($events[$key]);
                            break;
                        }
                    }
                    // Re-index array
                    $events = array_values($events);
                    // Save changes to file
                    file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));
                    header("Location: admin.php?success=Event deleted successfully");
                    exit;
                }
            }
        }

        // Handle edit requests
        if (isset($_GET['edit']) && !empty($_GET['edit'])) {
            $id = $_GET['edit'];
            foreach ($events as $event) {
                if ($event['id'] == $id) {
                    $name = $event['name'];
                    $date = $event['date'];
                    $description = $event['description'];
                    $link = $event['link'];
                    $isEditing = true;
                    break;
                }
            }
        }

        // Display success message
        if (isset($_GET['success'])) {
            echo '<div class="success-message">' . htmlspecialchars($_GET['success']) . '</div>';
        }
        ?>

        <!-- Add/Edit Event Form -->
        <div class="admin-panel">
            <h2><?php echo $isEditing ? '<i class="fas fa-edit"></i> Edit Event' : '<i class="fas fa-plus-circle"></i> Add New Event'; ?></h2>
            <form method="post" action="admin.php">
                <input type="hidden" name="action" value="save">
                <?php if ($isEditing): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Event Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="date">Event Date:</label>
                    <input type="text" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="link">Event Link:</label>
                    <input type="url" id="link" name="link" class="form-control" value="<?php echo htmlspecialchars($link); ?>" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $isEditing ? '<i class="fas fa-save"></i> Update Event' : '<i class="fas fa-plus"></i> Add Event'; ?>
                    </button>
                    <?php if ($isEditing): ?>
                    <a href="admin.php" class="btn btn-warning"><i class="fas fa-times"></i> Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Events Table -->
        <div class="admin-panel">
            <h2><i class="fas fa-list"></i> Manage Events</h2>
            <table class="events-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Link</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['id']); ?></td>
                        <td><?php echo htmlspecialchars($event['name']); ?></td>
                        <td><?php echo htmlspecialchars($event['date']); ?></td>
                        <td><?php echo htmlspecialchars(substr($event['description'], 0, 50)) . (strlen($event['description']) > 50 ? '...' : ''); ?></td>
                        <td><?php echo htmlspecialchars(substr($event['link'], 0, 30)) . (strlen($event['link']) > 30 ? '...' : ''); ?></td>
                        <td class="action-buttons">
                            <a href="admin.php?edit=<?php echo $event['id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                            <form method="post" action="admin.php" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($events)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No events found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="index.php" class="btn btn-primary"><i class="fas fa-eye"></i> View Public Events Page</a>
        </div>
    </div>
</body>
</html> 