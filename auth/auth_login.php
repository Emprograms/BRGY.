<?php
require __DIR__ . "/../config/db.php";

$msg = "";
$cls = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  $st = $pdo->prepare("SELECT id, role, full_name, username, password_hash FROM users WHERE username = ?");
  $st->execute([$username]);
  $u = $st->fetch();

  if (!$u || !password_verify($password, $u['password_hash'])) {
    $msg = "Invalid username or password.";
    $cls = "err";
  } else {
    $_SESSION['user'] = [
      'id' => (int)$u['id'],
      'role' => $u['role'],
      'full_name' => $u['full_name'],
      'username' => $u['username'],
    ];

    if ($u['role'] === 'admin') header("Location: /barangay-system/admin/dashboard.php");
    else header("Location: /barangay-system/resident/profile.php");
    exit;
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login</title>
  <link rel="stylesheet" href="/barangay-system/assets/style.css" />
</head>
<body>
  <div class="container" style="max-width:520px">
    <div class="card">
      <h2>Barangay Management System</h2>
      <p class="muted">Login</p>

      <form class="form" method="post">
        <label>Username
          <input name="username" required />
        </label>

        <label>Password
          <input type="password" name="password" required />
        </label>

        <button class="btn primary" type="submit">Login</button>
        <p class="muted">No account? <a href="/barangay-system/auth/register.php">Register</a></p>

        <p class="msg <?= htmlspecialchars($cls) ?>"><?= htmlspecialchars($msg) ?></p>
      </form>
    </div>
  </div>
</body>
</html>