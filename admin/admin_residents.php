<?php
require __DIR__ . "/../config/db.php";
require_role('admin');

$q = strtoupper(trim($_GET['q'] ?? ''));

if ($q !== '') {
  $st = $pdo->prepare("
    SELECT *,
      TIMESTAMPDIFF(YEAR,birthdate,CURDATE()) AS age
    FROM residents
    WHERE UPPER(CONCAT(last_name,', ',first_name,' ',IFNULL(middle_name,''))) LIKE ?
       OR UPPER(household_id) LIKE ?
    ORDER BY created_at DESC
    LIMIT 300
  ");
  $like = "%$q%";
  $st->execute([$like, $like]);
} else {
  $st = $pdo->query("
    SELECT *,
      TIMESTAMPDIFF(YEAR,birthdate,CURDATE()) AS age
    FROM residents
    ORDER BY created_at DESC
    LIMIT 300
  ");
}

$rows = $st->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Residents</title>
  <link rel="stylesheet" href="/BRGY./assets/style.css" />
</head>
<body>
  <div class="container">
    <div class="topbar">
      <div>
        <h2>Residents</h2>
        <div class="muted">Search and manage inhabitants</div>
      </div>
      <div class="row">
        <a class="btn" href="/BRGY./admin/dashboard.php">Dashboard</a>
        <a class="btn primary" href="/BRGY./admin/resident_add.php">+ Add</a>
        <a class="btn danger" href="/BRGY./auth/logout.php">Logout</a>
      </div>
    </div>

    <div class="card">
      <form class="row" method="get" style="align-items:flex-end">
        <label style="flex:1">Search
          <input name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search name, household..." />
        </label>
        <button class="btn" type="submit">Search</button>
        <a class="btn" href="/BRGY./admin/residents.php">Clear</a>
      </form>

      <p class="muted" style="margin-top:.6rem">Showing <?= count($rows) ?> record(s)</p>

      <div style="overflow:auto">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th><th>Sex</th><th>Age</th><th>Status</th><th>Household</th><th>Tags</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><b><?= htmlspecialchars($r['last_name'] . ', ' . $r['first_name']) ?></b></td>
                <td><?= htmlspecialchars($r['sex']) ?></td>
                <td><?= (int)$r['age'] ?></td>
                <td><?= htmlspecialchars($r['civil_status']) ?></td>
                <td><span class="badge"><?= htmlspecialchars($r['household_id']) ?></span></td>
                <td>
                  <?php if ((int)$r['is_pwd'] === 1): ?><span class="badge">PWD</span><?php endif; ?>
                  <?php if ((int)$r['is_solo_parent'] === 1): ?><span class="badge">Solo Parent</span><?php endif; ?>
                  <?php if ((int)$r['is_osy'] === 1): ?><span class="badge">OSY</span><?php endif; ?>
                  <?php if ((int)$r['is_pwd'] + (int)$r['is_solo_parent'] + (int)$r['is_osy'] === 0): ?>
                    <span class="muted">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
