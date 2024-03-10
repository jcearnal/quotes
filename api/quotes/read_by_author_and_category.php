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

// Check for author_id and category_id in the query parameters
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Validate input
if (!is_null($author_id) && !is_null($category_id)) {
    // Fetch quotes based on author_id and category_id
    $result = $quote->read_by_author_and_category($author_id, $category_id);

    $num = $result->rowCount();

    // Check if any quotes were found
    if($num > 0) {
        $quotes_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author_id' => $author_id,
                'category_id' => $category_id,
                'author_name' => $author_name,
                'category_name' => $category_name
            );
            array_push($quotes_arr, $quote_item);
        }

        // Convert the array to JSON and output
        echo json_encode($quotes_arr);
    } else {
        // If no quotes are found, return a message
        echo json_encode(array('message' => 'No Quotes Found'));
    }
} else {
    // Missing required parameters
    echo json_encode(array('message' => 'Missing Required Parameters'));
}
?>
