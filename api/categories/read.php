<?php
// Required headers for a GET request
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and category model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Category object
$category = new Category($db);

// Category read query
$result = $category->read();

// Check if any categories were found
if ($result->rowCount() > 0) {
    // Initialize an array to hold category data
    $categories_arr = array();

    // Retrieve the results as an associative array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $category_item = array(
            'id' => $id,
            'category' => $category
        );

        // Push each category into the categories array
        array_push($categories_arr, $category_item);
    }

    // Convert the array to JSON and output
    echo json_encode($categories_arr);
} else {
    // If no categories are found, return a message
    echo json_encode(
        array('message' => 'No Categories Found')
    );
}
