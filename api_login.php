<?php

// API Login Script

header('Content-Type: application/json');

// Sample users for demonstration purposes
$users = [
    'user1' => 'password1',
    'user2' => 'password2',
];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    // Validate credentials
    if (isset($users[$username]) && $users[$username] === $password) {
        // Create session data
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = true;

        // Return JSON response
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'session_data' => [
                'username' => $username,
                'session_id' => session_id(),
            ],
        ]);
    } else {
        // Invalid credentials
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid username or password',
        ]);
    }
} else {
    // Invalid request method
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method',
    ]);
}
?>
