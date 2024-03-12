<?php
// Headers required for a GET request
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

// Check for author_id in the query parameters
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;

// Validate input
if (!is_null($author_id)) {
    // Set the author_id property of the Quote
    $quote->author_id = $author_id;

    // Fetch quotes based on author_id
    $result = $quote->read_by_author($author_id);

    $num = $result->rowCount();

    // Check if any quotes were found
    if($num > 0) {
        $quotes_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quote_item = [
                'id' => $id,
                'quote' => $quote,
                'author' => $author_name, 
                'category' => $category_name, 
                // 'author_id' => $author_id,
                // 'category_id' => $category_id
            ];
            array_push($quotes_arr, $quote_item);
        }

        // Convert the array to JSON and output
        echo json_encode($quotes_arr);
    } else {
        // If no quotes are found for the author, return a message
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    // Missing author_id parameter
    echo json_encode(['message' => 'Missing Required Parameters']);
}
?>
