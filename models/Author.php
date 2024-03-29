<?php
class Author {
    private $conn;
    private $table = 'authors';

    // Author Properties
    public $id;
    public $author;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all authors
    public function read() {
        $query = 'SELECT id, author FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single author by ID
    public function read_single() {
        // Query to select a single author
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);
    
        // Bind ID
        $stmt->bindParam(':id', $this->id);
    
        // Execute query
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            // Set properties
            $this->author = $row['author'];
            return true;
        } else {
            // Author not found
            return false;
        }
    }

    // Create author
    public function create() {
        // Query to insert author
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author) RETURNING id;'; // Adjusted for PostgreSQL's RETURNING
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);
    
        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
    
        // Bind data
        $stmt->bindParam(':author', $this->name);
    
        // Execute query
        if ($stmt->execute()) {
            // Retrieve the last inserted ID
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
    }

    // Update author
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Clean the data
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        // If execution failed, print the error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Delete author
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            // Check if any row was deleted
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                // No row was deleted, indicating the author did not exist
                return false;
            }
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

}
