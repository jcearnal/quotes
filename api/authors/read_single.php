<?php
// Required headers for a GET request
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and author model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate database and connect
$database = new Database();
$db = $database->connect();

// Instantiate an Author object
$author = new Author($db);

// Get the ID from the URL if it exists
$author->id = isset($_GET['id']) ? $_GET['id'] : die();

// Fetch the author
$author->read_single();

// Check if the author property has been filled/found
if($author->author != null) {
    // Create an array containing the author
    $author_arr = array(
        'id' => $author->id,
        'author' => $author->author
    );

    // Make it JSON
    echo json_encode($author_arr);
} else {
    // No authors found with that id
    echo json_encode(
        array('message' => 'author_id Not Found')
    );
}
