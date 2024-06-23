<?php
require 'config.php';
require 'Controllers/UserController.php';
require 'Controllers/EmailController.php';
require 'Controllers/DbController.php';
require 'Middleware/authentication.php';
require __DIR__ . '/vendor/autoload.php';

// Set headers to allow cross-origin resource sharing (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    http_response_code(204);
    exit;
}

$userController = new UserController($pdo);
$emailController = new EmailController($pdo);
$dbController = new DbController($pdo);

$dbController->createTables();

// Example endpoint: GET /api/data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];

    switch ($endpoint) {
        case 'user':
            global $userController;
            $userController->getAllUsers();
            break;
        case 'userEmails':
            global $emailController;
            $userId = authenticate();
            if($userId) {
                $emailController->getUserEmail($userId);
            }
            break;
        default:
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];

    switch ($endpoint) {
        case 'user':
            global $userController;
            $data = json_decode(file_get_contents("php://input"), true);
            $userController->createUser($data);
            break;
        case 'login':
            global $userController;
            $data = json_decode(file_get_contents("php://input"), true);
            $userController->login($data);
            break;
        case 'email':
            global $emailController;
            $userId = authenticate();
            if($userId) {
                $data = json_decode(file_get_contents("php://input"), true);
                $user = $userController->getUserById($userId);
                $emailController->sendEmail($data, $user);
            }
            break;
        default:
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
            break;
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}