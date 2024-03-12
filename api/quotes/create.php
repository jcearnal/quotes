<?php
// Headers required for a POST request
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database, quote, author, and category models
include_once '../../config/Database.php';
include_once '../../models/Quote.php';
include_once '../../models/Author.php';
include_once '../../models/Category.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate quote, author, and category objects
$quote = new Quote($db);
$author = new Author($db);
$category = new Category($db);

// Get the raw posted data
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->quote) && isset($data->author_id) && isset($data->category_id)) {
    // Set IDs for author and category to check their existence
    $author->id = $data->author_id;
    $category->id = $data->category_id;

    if(!$author->read_single()) {
        echo json_encode(['message' => 'author_id Not Found']);
        return;
    } 
    
    if(!$category->read_single()) {
        echo json_encode(['message' => 'category_id Not Found']);
        return;
    }
    
    // Set quote properties
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Create the quote
    if($quote->create()) {
        echo json_encode([
            'id' => $db->lastInsertId('quotes_id_seq'),
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
        ]);
    } else {
        echo json_encode(['message' => 'Quote Not Created']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
?>
