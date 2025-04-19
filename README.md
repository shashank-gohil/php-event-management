# PHP Events Management System

A web-based events management system built with PHP, offering both a web interface and RESTful API for managing events and administrators.

## Features

- **Public Events Listing**: View upcoming events with details
- **Admin Panel**: Secure interface for managing events and users
- **RESTful API**: Full CRUD operations for events and admin users
- **Authentication**: Secure login and session management
- **Responsive Design**: Works on desktop and mobile devices

## Project Structure

```
php-project/
├── src/                    # Source code
│   ├── api/                # API endpoints
│   │   ├── config/         # API configuration
│   │   ├── controllers/    # API controllers
│   │   └── models/         # API data models
│   ├── controllers/        # Web application controllers
│   ├── models/             # Data models
│   ├── views/              # View templates
│   ├── config/             # Configuration files
│   └── public/             # Public assets
│       ├── css/            # Stylesheets
│       ├── js/             # JavaScript files
│       └── images/         # Images
├── events.json             # Events data store
├── admins.json             # Admin users data store
├── index.php               # Main entry point
├── admin.php               # Admin panel
└── admin_users.php         # Admin users management
```

## API Documentation

The API provides endpoints for:

1. **Authentication**
   - Login, logout, and status checking

2. **Events Management**
   - GET, POST, PUT, DELETE operations for events

3. **Admin Users Management**
   - GET, POST, PUT, DELETE operations for admin users

Complete API documentation is available in [api/README.md](api/README.md).

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/php-events-management.git
   ```

2. Place the files in your web server directory (e.g., Apache htdocs).

3. Access the application at:
   ```
   http://localhost/php-project/
   ```

4. Default admin credentials:
   - Username: admin
   - Password: password123

## Technologies Used

- PHP (No framework)
- HTML/CSS
- JavaScript
- JSON (for data storage)

## License

This project is open-source and available under the MIT License.

## Author

Your Name 