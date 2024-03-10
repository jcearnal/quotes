<?php
// Headers required for an UPDATE request
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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

// Set category ID to update
$category->id = $data->id;

// Check if ID and category fields are provided
if (!empty($category->id) && isset($data->category)) {
    // Set category property to be updated
    $category->category = $data->category;

    // Attempt to update category
    $updateResult = $category->update();

    if ($updateResult['success']) {
        // Check if any row was actually updated
        if ($updateResult['rowCount'] > 0) {
            echo json_encode(['message' => 'Category Updated']);
        } else {
            // No row was updated, meaning the category did not exist
            echo json_encode(['message' => 'Category Not Found']);
        }
    } else {
        echo json_encode(['message' => 'Category Not Updated', 'error' => $updateResult['error']]);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
