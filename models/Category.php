<?php
class Category {
    // Database connection and table name
    private $conn;
    private $table = 'categories';

    // Category properties
    public $id;
    public $category;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all categories
    public function read() {
        // Create query
        $query = 'SELECT id, category FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Read single category by ID
    public function read_single() {
        // Create query
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID parameter
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if any category was returned
        if ($row) {
            // Set properties
            $this->id = $row['id'];
            $this->category = $row['category'];
            return true; // Category found
        } else {
            return false; // No category found
        }
    }

    // Create category
    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category) RETURNING id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind data
        $stmt->bindParam(':category', $this->category);

        // Execute query and get inserted id
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Update category
    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return ['success' => true, 'rowCount' => $stmt->rowCount()];
        }
        
        return ['success' => false, 'error' => $stmt->error];
    }

    
    // Delete category
    public function delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);
    
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
    
        // Bind id
        $stmt->bindParam(':id', $this->id);
    
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
    
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}