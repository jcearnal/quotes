<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and category model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate a category object
$category = new Category($db);

// Get the raw posted data
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->id)) {
    // Set ID to delete
    $category->id = $data->id;

    // Attempt to delete the category and check if deletion was successful
    if ($category->delete()) {
        // Successful deletion
        echo json_encode(['id' => $category->id]);
    } else {
        // Attempted to delete a non-existing category
        echo json_encode(['id' => $category->id]);
    }
} else {
    // Missing ID parameter
    echo json_encode(['message' => 'Missing Required Parameters']);
}
