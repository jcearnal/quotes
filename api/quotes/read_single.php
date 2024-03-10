<?php
// Required headers for a GET request
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

// Get the ID from the URL if it exists
$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

// Fetch the quote
$result = $quote->read_single();

// Check if the quote property has been filled/found
if($result) {
    // Create an array containing the quote details
    $quote_arr = array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        //'author_id' => $quote->author_id,
        //'category_id' => $quote->category_id,
        'author' => $quote->author_name,
        'category' => $quote->category_name
    );

    // Make it JSON
    echo json_encode($quote_arr);
} else {
    // No quotes found with that id
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}
