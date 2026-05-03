<?php
require __DIR__ . "/../config/db.php";
require_role('admin');

// basic stats
$male = (int)$pdo->query("SELECT COUNT(*) c FROM residents WHERE sex='Male'")->fetch()['c'];
$female = (int)$pdo->query("SELECT COUNT(*) c FROM residents WHERE sex='Female'")->fetch()['c'];
$total = (int)$pdo->query("SELECT COUNT(*) c FROM residents")->fetch()['c'];
$households = (int)$pdo->query("SELECT COUNT(DISTINCT household_id) c FROM residents")->fetch()['c'];

$children = (int)$pdo->query("SELECT COUNT(*) c FROM residents WHERE TIMESTAMPDIFF(YEAR,birthdate,CURDATE()) < 18")->fetch()['c'];
$adults   = (int)$pdo->query("SELECT COUNT(*) c FROM residents WHERE TIMESTAMPDIFF(YEAR,birthdate,CURDATE()) BETWEEN 18 AND 59")->fetch()['c'];
$seniors  = (int)$pdo->query("SELECT COUNT(*) c FROM residents WHERE TIMESTAMPDIFF(YEAR,birthdate,CURDATE()) >= 60")->fetch()['c'];
$pwd      = (int)$pdo->query("SELECT COUNT(*) c FROM residents WHERE is_pwd=1")->fetch()['c'];
$solo     = (int)$pdo->query("SELECT COUNT(*) c FROM residents WHERE is_solo_parent=1")->fetch()['c'];
$osy      = (int)$pdo->query("SELECT COUNT(*) c FROM residents WHERE is_osy=1")->fetch()['c'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="/BRGY./assets/style.css" />
</head>
<body>
  <div class="container">
    <div class="topbar">
      <div>
        <h2>Registry of Inhabitants</h2>
        <div class="muted">Welcome, <?= htmlspecialchars($_SESSION['user']['full_name']) ?> (Admin)</div>
      </div>
      <div class="row">
        <a class="btn" href="/BRGY./admin/residents.php">Residents</a>
        <a class="btn primary" href="/BRGY./admin/resident_add.php">+ Add Inhabitant</a>
        <a class="btn danger" href="/BRGY./auth/logout.php">Logout</a>
      </div>
    </div>

    <div class="row">
      <div class="card" style="flex:1;background:#1d4ed8;color:#fff"><div>Male</div><div style="font-size:28px;font-weight:800"><?= $male ?></div></div>
      <div class="card" style="flex:1;background:#b91c1c;color:#fff"><div>Female</div><div style="font-size:28px;font-weight:800"><?= $female ?></div></div>
      <div class="card" style="flex:1;background:#166534;color:#fff"><div>Total Population</div><div style="font-size:28px;font-weight:800"><?= $total ?></div></div>
      <div class="card" style="flex:1;background:#0ea5e9;color:#fff"><div>Households</div><div style="font-size:28px;font-weight:800"><?= $households ?></div></div>
    </div>

    <div class="card" style="margin-top:1rem">
      <div class="row">
        <span class="badge">Children (&lt;18): <b><?= $children ?></b></span>
        <span class="badge">Adults (18-59): <b><?= $adults ?></b></span>
        <span class="badge">Seniors (60+): <b><?= $seniors ?></b></span>
        <span class="badge">PWD: <b><?= $pwd ?></b></span>
        <span class="badge">Solo Parent: <b><?= $solo ?></b></span>
        <span class="badge">OSY: <b><?= $osy ?></b></span>
      </div>
    </div>
  </div>
</body>
</html>
