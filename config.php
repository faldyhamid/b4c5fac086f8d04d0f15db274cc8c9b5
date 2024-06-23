<?php
// Database connection parameters
$dbhost = 'db';
$dbname = 'mydatabase';
$dbuser = 'myuser';
$dbpass = 'mypassword';

// Establish a database connection
try {
    $pdo = new PDO("pgsql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect to the database. " . $e->getMessage());
}