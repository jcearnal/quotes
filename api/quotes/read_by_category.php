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

// Check for category_id in the query parameters
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Validate input
if (!is_null($category_id)) {
    // Set the category_id property of the Quote
    $quote->category_id = $category_id;

    // Fetch quotes based on category_id
    $result = $quote->read_by_category($category_id);

    $num = $result->rowCount();

    // Check if any quotes were found
    if($num > 0) {
        $quotes_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quote_item = [
                'id' => $id,
                'quote' => $quote,
                'author_name' => $author_name, 
                'category_name' => $category_name, 
                // 'author_id' => $author_id,
                // 'category_id' => $category_id
            ];
            array_push($quotes_arr, $quote_item);
        }

        // Convert the array to JSON and output
        echo json_encode($quotes_arr);
    } else {
        // If no quotes are found for the category, return a message
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    // Missing category_id parameter
    echo json_encode(['message' => 'Missing Required Parameters']);
}
?>
