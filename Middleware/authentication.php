<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function authenticate()
{
    // Your secret key
    $key = $_ENV['JWT_SECRET'];

    // Authorization header format: Bearer <token>
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    // Extract JWT from header
    $token = str_replace('Bearer ', '', $authHeader);

    try {
        // Attempt to decode the token
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        // Access granted
        //http_response_code(200);
        //echo json_encode(['message' => 'Access granted', 'user' => $decoded->sub]);
        return $decoded->sub;
    } catch (Exception $e) {
        // Access denied (token invalid or expired)
        http_response_code(401);
        echo json_encode(['error' => 'Access denied', 'message' => $e->getMessage()]);
        return false;
    }
}