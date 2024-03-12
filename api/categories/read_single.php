<?php
// Required headers for a GET request
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and category model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate database and connect
$database = new Database();
$db = $database->connect();

// Instantiate a Category object
$category = new Category($db);

// Get the ID from the URL if it exists
$category->id = isset($_GET['id']) ? $_GET['id'] : die();

// Fetch the category
if($category->read_single()) {
    // Create an array containing the category
    $category_arr = array(
        'id' => $category->id,
        'category' => $category->category
    );

    // Make it JSON
    echo json_encode($category_arr);
} else {
    // No categories found with that ID
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
}
