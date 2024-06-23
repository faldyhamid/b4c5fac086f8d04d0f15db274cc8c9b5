<?php
require ('./Models/User.php');

use Firebase\JWT\JWT;

class UserController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function getAllUsers()
    {
        try {
            $response = $this->userModel->getAllUsers();

            http_response_code(200);
            echo json_encode($response);
        } catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
            echo json_encode($response);
        }
    }

    public function getUserById($id)
    {
        try {
            $response = $this->userModel->getUserById($id);
            return $response;
        } catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
            echo json_encode($response);
        }
    }

    public function createUser($data)
    {
        if (!empty($data['username']) && !empty($data['email'] && !empty($data['password']) && !empty($data['fullName']))) {
            try {
                $response = $this->userModel->createUser($data);

                http_response_code(200);
                echo json_encode($response);
            } catch (PDOException $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
                echo json_encode($response);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Name and email are required']);
        }
    }

    public function login($data)
    {
        // Your secret key
        $key = $_ENV['JWT_SECRET'];

        // Grab user data
        try {
            $user = $this->userModel->getUserByUsername($data['username']);
        } catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
            echo json_encode($response);
        }

        // Validate user credentials
        if ($data['username'] === $user['username'] && $data['password'] === $user['password']) {
            // Generate JWT
            $payload = [
                'iss' => 'emailshenanigans',       // Issuer of the token
                'sub' => $user['id'],              // Subject of the token (user id)
                'iat' => time(),                   // Issued at: time when the token was generated
                'exp' => time() + 3600             // Expire after 1 hour
            ];

            // Encode JWT
            $jwt = JWT::encode($payload, $key, 'HS256');

            // Return JWT to the client
            echo json_encode(['token' => $jwt]);
        } else {
            // Authentication failed
            http_response_code(401);
            echo json_encode(['error' => 'Authentication failed']);
        }
    }
}