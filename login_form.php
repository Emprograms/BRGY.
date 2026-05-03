<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login</title>
  <style>
    label{display:block;margin-top:12px}
    input{width:320px;max-width:100%;padding:10px}
    .primary{margin-top:12px;padding:10px 14px}
    .error{color:#b00020;margin-top:10px}
    .hint{margin-top:12px}
  </style>
</head>
<body>
  <h1>Login</h1>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="login.php" autocomplete="on">
    <label for="login-email">Email</label>
    <input id="login-email" name="email" type="email" placeholder="you@example.com" required />

    <label for="login-password">Password</label>
    <input id="login-password" name="password" type="password" placeholder="password" required />

    <button id="login-btn" class="primary" type="submit">Login</button>

    <p class="hint">No account? <a href="register_form.php" id="go-register">Register</a></p>
  </form>
</body>
</html>
