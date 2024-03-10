<?php
// Required headers for a GET request
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and author model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Author object
$author = new Author($db);

// Author read query
$result = $author->read();

// Check if any authors were found
if($result->rowCount() > 0) {
    // Initialize an array to hold author data
    $authors_arr = array();

    // Retrieve the results as an associative array
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $author_item = array(
            'id' => $id,
            'author' => $author
        );

        // Push each author into the authors array
        array_push($authors_arr, $author_item);
    }

    // Convert the array to JSON and output
    echo json_encode($authors_arr);
} else {
    // If no authors are found, return a message
    echo json_encode(
        array('message' => 'No Authors Found')
    );
}
