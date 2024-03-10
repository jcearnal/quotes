<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
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

// Validate input
if (!empty($data->id)) {
    // Set ID to delete
    $author->id = $data->id;

    // Attempt to delete the author and check if deletion was successful
    if ($author->delete()) {
        // Successful deletion
        echo json_encode(['id' => $author->id]);
    } else {
        // Attempted to delete a non-existing author
        echo json_encode(['id' => $author->id]);
    }
} else {
    // Missing ID parameter
    echo json_encode(['message' => 'Missing Required Parameters']);
}
