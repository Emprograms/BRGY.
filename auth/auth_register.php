<?php
require __DIR__ . "/../config/db.php";

$msg = "";
$cls = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = trim($_POST['full_name'] ?? '');
  $username  = trim($_POST['username'] ?? '');
  $password  = $_POST['password'] ?? '';
  $role      = ($_POST['role'] ?? 'resident') === 'admin' ? 'admin' : 'resident';

  if ($full_name === '' || $username === '' || $password === '') {
    $msg = "Please complete all fields.";
    $cls = "err";
  } else {
    // Check username
    $st = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $st->execute([$username]);
    if ($st->fetch()) {
      $msg = "Username already exists.";
      $cls = "err";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $st = $pdo->prepare("INSERT INTO users(role, full_name, username, password_hash) VALUES(?,?,?,?)");
      $st->execute([$role, $full_name, $username, $hash]);

      $msg = "Registered successfully. You can login now.";
      $cls = "ok";
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register</title>
  <link rel="stylesheet" href="/BMS/assets/style.css" />
</head>
<body>
  <div class="container" style="max-width:520px">
    <div class="card">
      <h2>Create Account</h2>
      <p class="muted">Register as Admin or Resident</p>

      <form class="form" method="post">
        <label>Full Name
          <input name="full_name" required />
        </label>

        <label>Username
          <input name="username" required />
        </label>

        <label>Password
          <input type="password" name="password" minlength="4" required />
        </label>

        <label>Role
          <select name="role">
            <option value="resident" selected>Resident</option>
            <option value="admin">Admin</option>
          </select>
        </label>

        <button class="btn primary" type="submit">Register</button>
        <p class="muted">Already have an account? <a href="/BMS/auth/login.php">Login</a></p>

        <p class="msg <?= htmlspecialchars($cls) ?>"><?= htmlspecialchars($msg) ?></p>
      </form>
    </div>
  </div>
</body>
</html>