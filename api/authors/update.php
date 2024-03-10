<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and author model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate an author object
$author = new Author($db);

// Get the raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set ID to update
$author->id = $data->id;

// Validate input
if (!empty($data->author) && !empty($data->id)) {
    // Set author property
    $author->author = $data->author;

    // Update the author
    if ($author->update()) {
        echo json_encode([
            'id' => $data->id,
            'author' => $data->author
        ]);
    } else {
        echo json_encode(['message' => 'Author Not Updated']);
    }
} else {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
