<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 2.5rem;
        }

        .header p {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .events-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            grid-gap: 30px;
        }

        .event-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .event-name {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 1.4rem;
        }

        .event-date {
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .event-date:before {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z'/%3E%3C/svg%3E");
            background-size: contain;
            margin-right: 8px;
        }

        .event-description {
            margin-bottom: 25px;
            color: #495057;
            line-height: 1.7;
        }

        .event-link {
            display: inline-block;
            background: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 500;
            border: none;
            box-shadow: 0 4px 6px rgba(76, 175, 80, 0.2);
            text-align: center;
        }

        .event-link:hover {
            background: #3d8b40;
            box-shadow: 0 6px 8px rgba(76, 175, 80, 0.3);
            transform: translateY(-2px);
        }
        
        .admin-link {
            display: block;
            text-align: center;
            margin-top: 60px;
        }
        
        .admin-link .event-link {
            background: #2c3e50;
            box-shadow: 0 4px 6px rgba(44, 62, 80, 0.2);
        }
        
        .admin-link .event-link:hover {
            background: #1a252f;
            box-shadow: 0 6px 8px rgba(44, 62, 80, 0.3);
        }
        
        .no-events {
            text-align: center;
            grid-column: 1 / -1;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .events-container {
                grid-template-columns: 1fr;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Upcoming Events</h1>
            <p>Stay updated with our latest events and don't miss any important meetings or sessions.</p>
        </div>
        
        <div class="events-container">
            <?php
            // Load events from JSON file
            $eventsFile = 'events.json';
            
            if (file_exists($eventsFile)) {
                $events = json_decode(file_get_contents($eventsFile), true);
            } else {
                // Fallback to static array if file doesn't exist
                $events = [
                    [
                        'name' => 'Team Meeting',
                        'date' => 'Monday, April 24, 2023 - 10:00 AM',
                        'description' => 'Weekly team sync to discuss project progress and upcoming milestones. All team members are required to attend.',
                        'link' => 'https://meet.google.com/abc-defg-hij'
                    ],
                    [
                        'name' => 'Training Session',
                        'date' => 'Wednesday, April 26, 2023 - 2:00 PM',
                        'description' => 'PHP Development best practices training session hosted by the senior development team. Open to all developers.',
                        'link' => 'https://meet.google.com/klm-nopq-rst'
                    ],
                    [
                        'name' => 'Client Presentation',
                        'date' => 'Friday, April 28, 2023 - 11:00 AM',
                        'description' => 'Presentation of new features to the client. Project managers and lead developers should attend.',
                        'link' => 'https://meet.google.com/uvw-xyz-123'
                    ],
                    [
                        'name' => 'Code Review Session',
                        'date' => 'Thursday, April 27, 2023 - 3:30 PM',
                        'description' => 'Team code review for the new feature implementation. Backend team members should prepare their code for review.',
                        'link' => 'https://meet.google.com/def-456-ghi'
                    ]
                ];
            }

            // Check if there are events to display
            if (!empty($events)) {
                // Loop through and display events
                foreach ($events as $event) {
                    echo '<div class="event-card">';
                    echo '<h2 class="event-name">' . htmlspecialchars($event['name']) . '</h2>';
                    echo '<p class="event-date">' . $event['date'] . '</p>';
                    echo '<p class="event-description">' . htmlspecialchars($event['description']) . '</p>';
                    echo '<a href="' . htmlspecialchars($event['link']) . '" class="event-link" target="_blank">Join Event</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="no-events"><p>No upcoming events at this time.</p></div>';
            }
            ?>
        </div>
        
        <div class="admin-link">
            <a href="login.php" class="event-link">Admin Panel</a>
        </div>
    </div>
</body>
</html>