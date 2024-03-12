<?php
class Quote {
    // DB stuff
    private $conn;
    private $table = 'quotes';

    // Quote Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author_name; // For joining with the authors table
    public $category_name; // For joining with the categories table

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get Quotes
    public function read() {
        // Create query
        $query = 'SELECT a.author as author_name, c.category as category_name, q.id, q.quote, q.author_id, q.category_id 
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Single Quote
    public function read_single() {
        // Create query
        $query = 'SELECT a.author as author_name, c.category as category_name, q.id, q.quote, q.author_id, q.category_id 
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE q.id = ? LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        if($row) {
            $this->quote = $row['quote'];
            $this->author_id = $row['author_id'];
            $this->category_id = $row['category_id'];
            $this->author_name = $row['author_name'];
            $this->category_name = $row['category_name'];
            return true;
        }
        
        return false;
    }

    // Get Quotes by Author
    public function read_by_author($author_id) {
        $query = 'SELECT a.author as author_name, c.category as category_name, q.id, q.quote, q.author_id, q.category_id 
                FROM ' . $this->table . ' q
                LEFT JOIN authors a ON q.author_id = a.id
                LEFT JOIN categories c ON q.category_id = c.id
                WHERE q.author_id = ?';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $author_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Get Quotes by Category
    public function read_by_category($category_id) {
        $query = 'SELECT a.author as author_name, c.category as category_name, q.id, q.quote, q.author_id, q.category_id 
                FROM ' . $this->table . ' q
                LEFT JOIN authors a ON q.author_id = a.id
                LEFT JOIN categories c ON q.category_id = c.id
                WHERE q.category_id = ?';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id, PDO::PARAM_INT); 
        $stmt->execute();
        return $stmt;
    }

    // Get quotes by author and category
    public function read_by_author_and_category($author_id, $category_id) {
        // Ensure author_id and category_id are correctly set
        $this->author_id = $author_id;
        $this->category_id = $category_id;
    
        $query = 'SELECT 
                    a.author as author_name, 
                    c.category as category_name, 
                    q.id, q.quote, q.author_id, q.category_id 
                  FROM 
                    ' . $this->table . ' q
                    LEFT JOIN authors a ON q.author_id = a.id
                    LEFT JOIN categories c ON q.category_id = c.id
                  WHERE 
                    q.author_id = :author_id AND q.category_id = :category_id';
    
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
    
        // Execute query
        $stmt->execute();
    
        return $stmt;
    }
    


    // Create Quote
    public function create() {
        // Create query
        $query = "INSERT INTO " . $this->table . " (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);
    
        // Clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    
        // Bind data
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
    
        // Execute query
        if($stmt->execute()) {
            return true;
        }
    
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
    
        return false;
    }

    // Update Quote
    public function update() {
        // Create query
        $query = "UPDATE " . $this->table . "
              SET quote = :quote, author_id = :author_id, category_id = :category_id
              WHERE id = :id";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
    
        return false;
    }

    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);
    
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
    
        // Bind data
        $stmt->bindParam(':id', $this->id);
    
        // Execute query
        if($stmt->execute()) {
            return $stmt; // Return the PDOStatement object
        }
    
        return false;
    }

}
