<?php
class Admin {
    private $id;
    private $username;
    private $password;
    private $created_at;
    
    public function __construct($id, $username, $password, $created_at = null) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getCreatedAt() {
        return $this->created_at;
    }
    
    // Setters
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }
    
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    
    // Convert to array
    public function toArray() {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'created_at' => $this->created_at
        ];
    }
    
    // Create sanitized array (no password)
    public function toSafeArray() {
        $data = $this->toArray();
        unset($data['password']);
        return $data;
    }
    
    // Create from array
    public static function fromArray($data) {
        return new self(
            $data['id'] ?? null,
            $data['username'] ?? '',
            $data['password'] ?? '',
            $data['created_at'] ?? null
        );
    }
    
    // Verify password
    public function verifyPassword($password) {
        // In a real application, you would use password_verify() here
        return $this->password === $password;
    }
}
?> 