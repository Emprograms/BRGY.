<?php
declare(strict_types=1);

$host = "localhost";
$db   = "barangay_db";
$user = "root";
$pass = ""; // XAMPP default

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function require_login(): void {
  if (empty($_SESSION['user'])) {
    header("Location: /BMS/auth/login.php");
    exit;
  }
}

function require_role(string $role): void {
  require_login();
  if (($_SESSION['user']['role'] ?? '') !== $role) {
    // redirect based on role
    $r = $_SESSION['user']['role'] ?? '';
    if ($r === 'admin') header("Location: /BMS/admin/dashboard.php");
    else header("Location: /BMS/resident/profile.php");
    exit;
  }
}