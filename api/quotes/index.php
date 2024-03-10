<?php
// Set headers for CORS compliance and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and quote model
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate database and connect
$database = new Database();
$db = $database->connect();

// Instantiate a quote object
$quote = new Quote($db);

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

switch ($method) {
    case 'GET':
        // Handle requests for quotes by a specific author and category
        if (isset($_GET['author_id']) && isset($_GET['category_id'])) {
            // Fetch quotes by author and category
            $quote->author_id = $_GET['author_id'];
            $quote->category_id = $_GET['category_id'];
            include 'read_by_author_and_category.php';
        }
        // Fetch a single quote
        elseif (isset($_GET['id'])) {
            include 'read_single.php';
        } 
        // Fetch all quotes
        else {
            include 'read.php';
        }
        break;
    case 'POST':
        include 'create.php';
        break;
    case 'PUT':
        include 'update.php';
        break;
    case 'DELETE':
        include 'delete.php';
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
?>
