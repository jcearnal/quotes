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
        if (isset($_GET['author_id']) && !isset($_GET['category_id'])) {
            // Fetch quotes by author only
            $quote->author_id = $_GET['author_id'];
            include 'read_by_author.php';
        } elseif (!isset($_GET['author_id']) && isset($_GET['category_id'])) {
            // Fetch quotes by category only
            $quote->category_id = $_GET['category_id'];
            include 'read_by_category.php';
        } elseif (isset($_GET['id'])) {
            // Fetch a single quote by ID
            include 'read_single.php';
        } else {
            // Fetch all quotes
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
