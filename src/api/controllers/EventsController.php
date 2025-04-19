<?php
require_once __DIR__ . '/../config/config.php';

class EventsController {
    // Get all events
    public function index() {
        $events = loadEvents();
        apiResponse(true, $events, 'Events retrieved successfully');
    }
    
    // Get a single event
    public function show($id) {
        $events = loadEvents();
        
        foreach ($events as $event) {
            if ($event['id'] == $id) {
                apiResponse(true, $event, 'Event retrieved successfully');
            }
        }
        
        apiResponse(false, null, 'Event not found', 404);
    }
    
    // Create a new event (requires authentication)
    public function create() {
        // Check authentication
        $username = requireAuth();
        
        $data = getInputData();
        
        // Validate required fields
        if (!isset($data['name']) || !isset($data['date']) || !isset($data['description']) || !isset($data['link'])) {
            apiResponse(false, null, 'All fields (name, date, description, link) are required', 400);
        }
        
        $events = loadEvents();
        
        // Create new event with ID
        $maxId = 0;
        foreach ($events as $event) {
            if ($event['id'] > $maxId) {
                $maxId = $event['id'];
            }
        }
        
        $newEvent = [
            'id' => $maxId + 1,
            'name' => $data['name'],
            'date' => $data['date'],
            'description' => $data['description'],
            'link' => $data['link']
        ];
        
        $events[] = $newEvent;
        
        if (saveEvents($events)) {
            apiResponse(true, $newEvent, 'Event created successfully', 201);
        } else {
            apiResponse(false, null, 'Failed to create event', 500);
        }
    }
    
    // Update an event (requires authentication)
    public function update($id) {
        // Check authentication
        $username = requireAuth();
        
        $data = getInputData();
        
        // Validate required fields
        if (!isset($data['name']) || !isset($data['date']) || !isset($data['description']) || !isset($data['link'])) {
            apiResponse(false, null, 'All fields (name, date, description, link) are required', 400);
        }
        
        $events = loadEvents();
        $found = false;
        
        foreach ($events as $key => $event) {
            if ($event['id'] == $id) {
                $events[$key] = [
                    'id' => (int)$id,
                    'name' => $data['name'],
                    'date' => $data['date'],
                    'description' => $data['description'],
                    'link' => $data['link']
                ];
                $found = true;
                $updatedEvent = $events[$key];
                break;
            }
        }
        
        if (!$found) {
            apiResponse(false, null, 'Event not found', 404);
        }
        
        if (saveEvents($events)) {
            apiResponse(true, $updatedEvent, 'Event updated successfully');
        } else {
            apiResponse(false, null, 'Failed to update event', 500);
        }
    }
    
    // Delete an event (requires authentication)
    public function delete($id) {
        // Check authentication
        $username = requireAuth();
        
        $events = loadEvents();
        $found = false;
        
        foreach ($events as $key => $event) {
            if ($event['id'] == $id) {
                unset($events[$key]);
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            apiResponse(false, null, 'Event not found', 404);
        }
        
        // Re-index array
        $events = array_values($events);
        
        if (saveEvents($events)) {
            apiResponse(true, null, 'Event deleted successfully');
        } else {
            apiResponse(false, null, 'Failed to delete event', 500);
        }
    }
}
?> 