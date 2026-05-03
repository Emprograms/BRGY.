<?php
require __DIR__ . "/../config/db.php";
require_role('resident');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Resident Profile</title>
  <link rel="stylesheet" href="/barangay-system/assets/style.css" />
</head>
<body>
  <div class="container">
    <div class="topbar">
      <div>
        <h2>Resident Portal</h2>
        <div class="muted">Welcome, <?= htmlspecialchars($_SESSION['user']['full_name']) ?></div>
      </div>
      <div class="row">
        <a class="btn danger" href="/barangay-system/auth/logout.php">Logout</a>
      </div>
    </div>

    <div class="card">
      <h3>Your Account</h3>
      <p><b>Full Name:</b> <?= htmlspecialchars($_SESSION['user']['full_name']) ?></p>
      <p><b>Username:</b> <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
      <p><b>Role:</b> <?= htmlspecialchars($_SESSION['user']['role']) ?></p>

      <p class="muted">
        Next step (optional): link this account to a record in the residents table (so residents can view/update their own info).
      </p>
    </div>
  </div>
</body>
</html>