<?php

// User Registration API

header('Content-Type: application/json');

// Database connection
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the incoming data
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    // Insert user into database
    $query = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'User registered successfully!']);
    } else {
        echo json_encode(['message' => 'User registration failed!']);
    }
    $stmt->close();
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}

?>
