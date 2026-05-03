<?php
require __DIR__ . "/../config/db.php";
require_role('admin');

$msg = ""; $cls = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $last = strtoupper(trim($_POST['last_name'] ?? ''));
  $first = strtoupper(trim($_POST['first_name'] ?? ''));
  $middle = strtoupper(trim($_POST['middle_name'] ?? ''));
  $sex = $_POST['sex'] ?? '';
  $birthdate = $_POST['birthdate'] ?? '';
  $civil = $_POST['civil_status'] ?? '';
  $household = strtoupper(trim($_POST['household_id'] ?? ''));

  $is_pwd = isset($_POST['is_pwd']) ? 1 : 0;
  $is_solo = isset($_POST['is_solo_parent']) ? 1 : 0;
  $is_osy = isset($_POST['is_osy']) ? 1 : 0;

  if ($last==='' || $first==='' || $sex==='' || $birthdate==='' || $civil==='' || $household==='') {
    $msg = "Please complete all required fields.";
    $cls = "err";
  } else {
    $st = $pdo->prepare("
      INSERT INTO residents(created_by_user_id,last_name,first_name,middle_name,sex,birthdate,civil_status,household_id,is_pwd,is_solo_parent,is_osy)
      VALUES(?,?,?,?,?,?,?,?,?,?,?)
    ");
    $st->execute([
      (int)$_SESSION['user']['id'], $last, $first, $middle ?: null, $sex, $birthdate, $civil, $household,
      $is_pwd, $is_solo, $is_osy
    ]);
    $msg = "Resident added successfully.";
    $cls = "ok";
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add Resident</title>
  <link rel="stylesheet" href="/BRGY./assets/style.css" />
</head>
<body>
  <div class="container">
    <div class="topbar">
      <div>
        <h2>Add Inhabitant</h2>
        <div class="muted">Admin: <?= htmlspecialchars($_SESSION['user']['full_name']) ?></div>
      </div>
      <div class="row">
        <a class="btn" href="/BRGY./admin/dashboard.php">Dashboard</a>
        <a class="btn" href="/BRGY./admin/residents.php">Residents</a>
        <a class="btn danger" href="/BRGY./auth/logout.php">Logout</a>
      </div>
    </div>

    <div class="card">
      <form class="form grid2" method="post">
        <label>Last Name* <input name="last_name" required></label>
        <label>First Name* <input name="first_name" required></label>
        <label>Middle Name <input name="middle_name"></label>

        <label>Sex*
          <select name="sex" required>
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
          </select>
        </label>

        <label>Birthdate* <input type="date" name="birthdate" required></label>

        <label>Civil Status*
          <select name="civil_status" required>
            <option value="">Select</option>
            <option>Single</option>
            <option>Married</option>
            <option>Widowed</option>
            <option>Separated</option>
          </select>
        </label>

        <label>Household ID* <input name="household_id" required placeholder="e.g., TAGUIAM-02"></label>

        <label><input type="checkbox" name="is_pwd"> PWD</label>
        <label><input type="checkbox" name="is_solo_parent"> Solo Parent</label>
        <label><input type="checkbox" name="is_osy"> OSY</label>

        <div style="grid-column:1/-1">
          <button class="btn primary" type="submit">Save</button>
          <p class="msg <?= htmlspecialchars($cls) ?>"><?= htmlspecialchars($msg) ?></p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
