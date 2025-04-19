# Events Management API Documentation

This document provides details about the Events Management API endpoints, how to use them, and example requests/responses.

## Base URL

All API requests should be sent to: `/api/`

## Authentication

The API uses session-based authentication. Most endpoints require authentication except for:
- Getting the list of events
- Getting a single event
- Authentication endpoints (login, status check)

## Response Format

All API responses follow this standard format:

```json
{
  "success": true/false,
  "message": "A descriptive message about the result",
  "data": {
    // The response data (if any)
  }
}
```

## API Endpoints

### Authentication

#### Login

- **URL**: `/api/auth.php`
- **Method**: `POST`
- **Authentication Required**: No
- **Request Body**:
  ```json
  {
    "username": "admin",
    "password": "password123"
  }
  ```
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "You have been logged in successfully",
    "data": {
      "username": "admin",
      "message": "Login successful"
    }
  }
  ```
- **Error Response (401)**:
  ```json
  {
    "success": false,
    "message": "Invalid username or password",
    "data": null
  }
  ```

#### Logout

- **URL**: `/api/auth.php?action=logout`
- **Method**: `POST`
- **Authentication Required**: Yes
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "You have been logged out successfully",
    "data": null
  }
  ```

#### Check Authentication Status

- **URL**: `/api/auth.php?action=status`
- **Method**: `GET`
- **Authentication Required**: No
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "User is authenticated",
    "data": {
      "authenticated": true,
      "username": "admin"
    }
  }
  ```
  or
  ```json
  {
    "success": true,
    "message": "User is not authenticated",
    "data": {
      "authenticated": false
    }
  }
  ```

### Events

#### Get All Events

- **URL**: `/api/events.php`
- **Method**: `GET`
- **Authentication Required**: No
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Events retrieved successfully",
    "data": [
      {
        "id": 1,
        "name": "Team Meeting",
        "date": "Monday, April 24, 2023 - 10:00 AM",
        "description": "Weekly team sync to discuss project progress",
        "link": "https://meet.google.com/abc-defg-hij"
      },
      // more events...
    ]
  }
  ```

#### Get Single Event

- **URL**: `/api/events.php?id=1`
- **Method**: `GET`
- **Authentication Required**: No
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Event retrieved successfully",
    "data": {
      "id": 1,
      "name": "Team Meeting",
      "date": "Monday, April 24, 2023 - 10:00 AM",
      "description": "Weekly team sync to discuss project progress",
      "link": "https://meet.google.com/abc-defg-hij"
    }
  }
  ```
- **Error Response (404)**:
  ```json
  {
    "success": false,
    "message": "Event not found",
    "data": null
  }
  ```

#### Create Event

- **URL**: `/api/events.php`
- **Method**: `POST`
- **Authentication Required**: Yes
- **Request Body**:
  ```json
  {
    "name": "New Team Meeting",
    "date": "Monday, May 1, 2023 - 10:00 AM",
    "description": "Monthly team sync to discuss project progress",
    "link": "https://meet.google.com/new-link"
  }
  ```
- **Success Response (201)**:
  ```json
  {
    "success": true,
    "message": "Event created successfully",
    "data": {
      "id": 5,
      "name": "New Team Meeting",
      "date": "Monday, May 1, 2023 - 10:00 AM",
      "description": "Monthly team sync to discuss project progress",
      "link": "https://meet.google.com/new-link"
    }
  }
  ```

#### Update Event

- **URL**: `/api/events.php?id=1`
- **Method**: `PUT`
- **Authentication Required**: Yes
- **Request Body**:
  ```json
  {
    "name": "Updated Team Meeting",
    "date": "Monday, May 1, 2023 - 11:00 AM",
    "description": "Updated description",
    "link": "https://meet.google.com/updated-link"
  }
  ```
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Event updated successfully",
    "data": {
      "id": 1,
      "name": "Updated Team Meeting",
      "date": "Monday, May 1, 2023 - 11:00 AM",
      "description": "Updated description",
      "link": "https://meet.google.com/updated-link"
    }
  }
  ```
- **Error Response (404)**:
  ```json
  {
    "success": false,
    "message": "Event not found",
    "data": null
  }
  ```

#### Delete Event

- **URL**: `/api/events.php?id=1`
- **Method**: `DELETE`
- **Authentication Required**: Yes
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Event deleted successfully",
    "data": null
  }
  ```
- **Error Response (404)**:
  ```json
  {
    "success": false,
    "message": "Event not found",
    "data": null
  }
  ```

### Admin Users

#### Get All Admin Users

- **URL**: `/api/admins.php`
- **Method**: `GET`
- **Authentication Required**: Yes
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Admin users retrieved successfully",
    "data": [
      {
        "id": 1,
        "username": "admin",
        "created_at": "2023-04-22 10:00:00"
      },
      // more admin users...
    ]
  }
  ```

#### Get Single Admin User

- **URL**: `/api/admins.php?id=1`
- **Method**: `GET`
- **Authentication Required**: Yes
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Admin user retrieved successfully",
    "data": {
      "id": 1,
      "username": "admin",
      "created_at": "2023-04-22 10:00:00"
    }
  }
  ```
- **Error Response (404)**:
  ```json
  {
    "success": false,
    "message": "Admin user not found",
    "data": null
  }
  ```

#### Create Admin User

- **URL**: `/api/admins.php`
- **Method**: `POST`
- **Authentication Required**: Yes
- **Request Body**:
  ```json
  {
    "username": "newadmin",
    "password": "password456"
  }
  ```
- **Success Response (201)**:
  ```json
  {
    "success": true,
    "message": "Admin user created successfully",
    "data": {
      "id": 2,
      "username": "newadmin",
      "created_at": "2023-04-23 15:30:00"
    }
  }
  ```
- **Error Response (409)**:
  ```json
  {
    "success": false,
    "message": "Username already exists",
    "data": null
  }
  ```

#### Update Admin User

- **URL**: `/api/admins.php?id=2`
- **Method**: `PUT`
- **Authentication Required**: Yes
- **Request Body**:
  ```json
  {
    "username": "updatedadmin",
    "password": "newpassword789" // Optional, omit to keep current password
  }
  ```
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Admin user updated successfully",
    "data": {
      "id": 2,
      "username": "updatedadmin",
      "created_at": "2023-04-23 15:30:00"
    }
  }
  ```
- **Error Response (404)**:
  ```json
  {
    "success": false,
    "message": "Admin user not found",
    "data": null
  }
  ```

#### Delete Admin User

- **URL**: `/api/admins.php?id=2`
- **Method**: `DELETE`
- **Authentication Required**: Yes
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Admin user deleted successfully",
    "data": null
  }
  ```
- **Error Response (403)**:
  ```json
  {
    "success": false,
    "message": "You cannot delete your own account",
    "data": null
  }
  ```
- **Error Response (404)**:
  ```json
  {
    "success": false,
    "message": "Admin user not found",
    "data": null
  }
  ```

## Error Responses

### General Errors

- **401 Unauthorized**:
  ```json
  {
    "success": false,
    "message": "Unauthorized access",
    "data": null
  }
  ```

- **400 Bad Request**:
  ```json
  {
    "success": false,
    "message": "Missing required fields",
    "data": null
  }
  ```

- **404 Not Found**:
  ```json
  {
    "success": false,
    "message": "Resource not found",
    "data": null
  }
  ```

- **500 Internal Server Error**:
  ```json
  {
    "success": false,
    "message": "Failed to perform operation",
    "data": null
  }
  ```

## Examples Using JavaScript Fetch API

### Login Example

```javascript
fetch('/api/auth.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    username: 'admin',
    password: 'password123'
  }),
  credentials: 'include' // Important for session cookies
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

### Creating an Event Example

```javascript
fetch('/api/events.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    name: 'New Team Meeting',
    date: 'Monday, May 1, 2023 - 10:00 AM',
    description: 'Monthly team sync to discuss project progress',
    link: 'https://meet.google.com/new-link'
  }),
  credentials: 'include' // Important for session cookies
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
``` 