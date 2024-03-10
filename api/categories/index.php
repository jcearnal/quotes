<?php
// Set headers for CORS compliance and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and category model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate database and connect
$database = new Database();
$db = $database->connect();

// Instantiate a category object
$category = new Category($db);

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // If an ID specified, include read_single.php
            include 'read_single.php';
        } else {
            // If no ID specified, include read.php
            include 'read.php';
        }
        break;
    case 'POST':
        // Include create.php for POST requests
        include 'create.php';
        break;
    case 'PUT':
        // Include update.php for PUT requests
        include 'update.php';
        break;
    case 'DELETE':
        // Include delete.php for DELETE requests
        include 'delete.php';
        break;
    default:
        // If the method is not supported, send a 405
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
