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
        // Handle requests for a single category or all categories
        if (isset($_GET['id'])) {
            $category->id = $_GET['id'];
            $category->read_single();

            // Check if the category property has been set (meaning a category was found)
            if ($category->category != null) {
                echo json_encode([
                    'id' => $category->id,
                    'category' => $category->category
                ]);
            } else {
                echo json_encode(['message' => 'category_id Not Found']);
            }
        } else {
            $result = $category->read();
            $num = $result->rowCount();

            if ($num > 0) {
                $categories_arr = array();
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $category_item = ['id' => $id, 'category' => $category];
                    array_push($categories_arr, $category_item);
                }
                echo json_encode($categories_arr);
            } else {
                echo json_encode(['message' => 'No Categories Found']);
            }
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
        // Method Not Allowed
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
