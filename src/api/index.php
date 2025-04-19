<?php
// API Router
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/EventsController.php';
require_once __DIR__ . '/controllers/AdminsController.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Parse the request URL
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/php-project/src/api';
$path = str_replace($basePath, '', $requestUri);
$path = trim($path, '/');
$segments = explode('/', $path);
$resource = $segments[0] ?? '';
$id = $segments[1] ?? null;
$action = $segments[2] ?? null;

// Handle routing based on resource and HTTP method
switch ($resource) {
    case 'auth':
        $controller = new AuthController();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($action === 'logout') {
                $controller->logout();
            } else {
                $controller->login();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'status') {
            $controller->status();
        }
        break;
        
    case 'events':
        $controller = new EventsController();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($id) {
                $controller->show($id);
            } else {
                $controller->index();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $id) {
            $controller->update($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $id) {
            $controller->delete($id);
        }
        break;
        
    case 'admins':
        $controller = new AdminsController();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($id) {
                $controller->show($id);
            } else {
                $controller->index();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $id) {
            $controller->update($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $id) {
            $controller->delete($id);
        }
        break;
        
    default:
        // Return API overview or documentation link
        apiResponse(true, [
            'message' => 'Welcome to the Events Management API',
            'version' => '1.0',
            'endpoints' => [
                'auth' => [
                    'POST /api/auth' => 'Login',
                    'POST /api/auth/logout' => 'Logout',
                    'GET /api/auth/status' => 'Check authentication status'
                ],
                'events' => [
                    'GET /api/events' => 'Get all events',
                    'GET /api/events/{id}' => 'Get a specific event',
                    'POST /api/events' => 'Create a new event (requires auth)',
                    'PUT /api/events/{id}' => 'Update an event (requires auth)',
                    'DELETE /api/events/{id}' => 'Delete an event (requires auth)'
                ],
                'admins' => [
                    'GET /api/admins' => 'Get all admin users (requires auth)',
                    'GET /api/admins/{id}' => 'Get a specific admin user (requires auth)',
                    'POST /api/admins' => 'Create a new admin user (requires auth)',
                    'PUT /api/admins/{id}' => 'Update an admin user (requires auth)',
                    'DELETE /api/admins/{id}' => 'Delete an admin user (requires auth)'
                ]
            ],
            'documentation' => '/api/README.md'
        ], 'API Overview');
}

// If we reach here, the endpoint is invalid
apiResponse(false, null, 'Invalid API endpoint', 404);
?> 