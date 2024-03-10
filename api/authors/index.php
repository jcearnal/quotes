<?php
// Required headers for CORS compliance and setting the content type to JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

// Including database and model files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiating Database and connecting
$database = new Database();
$db = $database->connect();

// Instantiating an author object
$author = new Author($db);

// Handling preflight CORS requests
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

// Check for query parameters
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Routing to the correct file based on the HTTP method
switch ($method) {
    case 'GET':
        if ($id) {
            include 'read_single.php';
        } else {
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
        // Method not supported
        header('HTTP/1.1 405 Method Not Allowed');
        break;
}
