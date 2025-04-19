<?php
class Event {
    private $id;
    private $name;
    private $date;
    private $description;
    private $link;
    
    public function __construct($id, $name, $date, $description, $link) {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->description = $description;
        $this->link = $link;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function getLink() {
        return $this->link;
    }
    
    // Setters
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function setDate($date) {
        $this->date = $date;
        return $this;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    public function setLink($link) {
        $this->link = $link;
        return $this;
    }
    
    // Convert to array
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => $this->date,
            'description' => $this->description,
            'link' => $this->link
        ];
    }
    
    // Create from array
    public static function fromArray($data) {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? '',
            $data['date'] ?? '',
            $data['description'] ?? '',
            $data['link'] ?? ''
        );
    }
}
?> 