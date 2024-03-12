<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and model files
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

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set ID to update
$quote->id = $data->id ?? null;

// Validate input
if (empty($quote->id) || empty($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    return;
}

// Check if author exists
$author->id = $data->author_id;
if (!$author->read_single()) {
    echo json_encode(['message' => 'author_id Not Found']);
    return;
}

// Check if category exists
$category->id = $data->category_id;
if (!$category->read_single()) {
    echo json_encode(['message' => 'category_id Not Found']);
    return;
}

// Update quote properties
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

// Update quote
if ($quote->update()) {
    echo json_encode([
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author_id' => $quote->author_id,
        'category_id' => $quote->category_id
    ]);
} else {
    echo json_encode(['message' => 'No Quotes Found']);
}
