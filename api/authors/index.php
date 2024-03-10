<?php
// Set headers for CORS compliance and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and author model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate database and connect
$database = new Database();
$db = $database->connect();

// Instantiate an author object
$author = new Author($db);

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

switch ($method) {
    case 'GET':
        // Handle requests for a single author or all authors
        if (isset($_GET['id'])) {
            $author->id = $_GET['id'];
            if ($author->read_single()) {
                echo json_encode(['id' => $author->id, 'author' => $author->author]);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(['message' => 'author_id Not Found']);
            }
        } else {
            $result = $author->read();
            $num = $result->rowCount();
            if ($num > 0) {
                $authors_arr = array();
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $author_item = array('id' => $id, 'author' => $author);
                    array_push($authors_arr, $author_item);
                }
                echo json_encode($authors_arr);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(['message' => 'No Authors Found']);
            }
        }
        break;
    // Continue with cases for POST, PUT, DELETE as previously defined
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
        http_response_code(405); // Method Not Allowed
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}

