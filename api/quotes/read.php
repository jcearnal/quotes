<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and quote model
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quote = new Quote($db);

// Quote read query
$result = $quote->read();

// Get row count
$num = $result->rowCount();

// Check if any quotes exist
if ($num > 0) {
    // Initialize an array for quotes
    $quotes_arr = [];

    // Retrieve the records
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $quote_item = [
            'id' => $id,
            'quote' => $quote,
            //'author_id' => $author_id, 
            //'category_id' => $category_id,  
            'author' => $author_name,
            'category' => $category_name
        ];

        // Push each quote into the array directly
        array_push($quotes_arr, $quote_item);
    }

    // Convert to JSON and output directly without the 'data' wrapper
    echo json_encode($quotes_arr);
} else {
    // No quotes found
    echo json_encode(['message' => 'No Quotes Found']);
}
