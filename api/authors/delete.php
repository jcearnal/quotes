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

    // Attempt to delete the author
    $result = $author->delete();
    
    if ($result === true) {
        echo json_encode(
            array('id' => $author->id, 'message' => 'Author Deleted')
        );
    } else if ($result === false) {
        echo json_encode(
            array('message' => 'No Authors Found')
        );
    } else {
        // Covers any other conditions handled by the delete method
        echo json_encode(
            array('message' => 'Author Not Deleted')
        );
    }
} else {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}

