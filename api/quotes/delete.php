<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and quote model
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate a quote object
$quote = new Quote($db);

// Get the raw posted data
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->id)) {
    // Set ID property of quote to delete
    $quote->id = $data->id;

    // Attempt to delete the quote
    $result = $quote->delete();
    
    // Check if the delete was successful
    if ($result) {
        $num = $result->rowCount();

        // Check if any row was affected
        if ($num > 0) {
            echo json_encode(['id' => $quote->id]);
        } else {
            echo json_encode(['message' => 'No Quotes Found']);
        }
    } else {
        echo json_encode(['message' => 'Quote Not Deleted']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
?>
