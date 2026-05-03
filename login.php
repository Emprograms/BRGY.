<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: login_form.php');
  exit;
}

$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
  $_SESSION['login_error'] = 'Email and password are required.';
  header('Location: login_form.php');
  exit;
}

/*
  Update these DB settings:
*/
$DB_HOST = '127.0.0.1';
$DB_NAME = 'your_db';
$DB_USER = 'your_user';
$DB_PASS = 'your_pass';

try {
  $pdo = new PDO(
    "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );

  // Assumes a `users` table with columns: id, email, password_hash
  $stmt = $pdo->prepare('SELECT id, email, password_hash FROM users WHERE email = :email LIMIT 1');
  $stmt->execute([':email' => $email]);
  $user = $stmt->fetch();

  // Generic error message (don’t reveal whether email exists)
  if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['login_error'] = 'Invalid email or password.';
    header('Location: login_form.php');
    exit;
  }

  // Optional: prevent session fixation
  session_regenerate_id(true);

  $_SESSION['user_id'] = (int)$user['id'];
  $_SESSION['user_email'] = $user['email'];

  header('Location: dashboard.php');
  exit;

} catch (Throwable $e) {
  // In production: log the error instead of showing details
  $_SESSION['login_error'] = 'Server error. Please try again later.';
  header('Location: login_form.php');
  exit;
}
