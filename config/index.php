<?php
require __DIR__ . "/config/db.php";

// If already logged in, redirect by role
if (!empty($_SESSION['user'])) {
  if (($_SESSION['user']['role'] ?? '') === 'admin') {
    header("Location: /BMS/admin/dashboard.php"); exit;
  }
  header("Location: /BMS/resident/profile.php"); exit;
}

$msg = "";
$msgClass = "";

// Handle Register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'register') {
  $fullName = trim($_POST['full_name'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $role     = ($_POST['role'] ?? 'resident') === 'admin' ? 'admin' : 'resident';

  if ($fullName === '' || $email === '' || $password === '') {
    $msg = "Please complete all fields.";
    $msgClass = "err";
  } else {
    // check duplicate email
    $st = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $st->execute([$email]);
    if ($st->fetch()) {
      $msg = "Email already registered. Please login.";
      $msgClass = "err";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $st = $pdo->prepare("INSERT INTO users(role, full_name, username, password_hash) VALUES(?,?,?,?)");
      $st->execute([$role, $fullName, $email, $hash]);

      $msg = "Registered successfully. You can login now.";
      $msgClass = "ok";
    }
  }
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $st = $pdo->prepare("SELECT id, role, full_name, username, password_hash FROM users WHERE username = ?");
  $st->execute([$email]);
  $u = $st->fetch();

  if (!$u || !password_verify($password, $u['password_hash'])) {
    $msg = "Invalid email or password.";
    $msgClass = "err";
  } else {
    $_SESSION['user'] = [
      'id' => (int)$u['id'],
      'role' => $u['role'],
      'full_name' => $u['full_name'],
      'username' => $u['username'],
    ];

    if ($u['role'] === 'admin') { header("Location: /BMS/admin/dashboard.php"); exit; }
    header("Location: /BMS/resident/profile.php"); exit;
  }
}

$activeTab = $_POST['action'] ?? 'login'; // keep tab after submit
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Barangay Bonbon Portal</title>

  <link rel="stylesheet" href="/BMS/styles.css" />

  <!-- Login background styles (same idea as your HTML) -->
  <style>
    body.auth-bg {
      min-height: 100vh;
      margin: 0;
      background-image: url("assets/muni.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    body.auth-bg::before {
      content: "";
      position: fixed;
      inset: 0;
      background: linear-gradient(135deg, rgba(0,0,0,0.35), rgba(0,0,0,0.55));
      z-index: 0;
      pointer-events: none;
    }
    main.container { position: relative; z-index: 1; }

    .card {
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(6px);
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.25);
      padding: 20px;
      max-width: 420px;
      width: 95%;
    }

    .tabs { display:flex; gap:8px; margin-bottom: 12px; }
    .tabs button{
      flex:1;
      padding:10px 12px;
      border:1px solid rgba(0,0,0,0.08);
      background:#fff;
      border-radius:10px;
      cursor:pointer;
      font-weight:600;
    }
    .tabs button.active{
      background:#2563eb;
      color:#fff;
      border-color:#2563eb;
    }

    .form { display:grid; gap:10px; }
    .form h2 { margin: 6px 0 0; }
    .form label { font-size: 14px; color:#111827; }

    .form input, .form select{
      padding:10px 12px;
      border-radius:10px;
      border:1px solid rgba(0,0,0,0.15);
      outline:none;
    }
    .form input:focus, .form select:focus{
      border-color: rgba(37,99,235,.6);
      box-shadow: 0 0 0 3px rgba(37,99,235,.15);
    }

    button.primary{
      padding:10px 12px;
      border-radius:10px;
      border:none;
      background:#2563eb;
      color:#fff;
      cursor:pointer;
      font-weight:700;
    }
    .hint{ margin: 0; font-size: 14px; opacity:.85; }
    .hidden{ display:none; }

    .msg{ margin-top: 8px; min-height: 18px; font-size: 14px; }
    .msg.ok{ color:#15803d; }
    .msg.err{ color:#dc2626; }
  </style>
</head>

<body class="auth-bg">
  <main class="container">
    <section id="auth" class="card">
      <div class="tabs">
        <button id="show-login" class="<?= $activeTab === 'login' ? 'active' : '' ?>">Login</button>
        <button id="show-register" class="<?= $activeTab === 'register' ? 'active' : '' ?>">Register</button>
      </div>

      <!-- LOGIN -->
      <form id="login-form" class="form <?= $activeTab === 'login' ? '' : 'hidden' ?>" method="post">
        <input type="hidden" name="action" value="login" />
        <h2>Login</h2>

        <label>Email</label>
        <input name="email" type="email" placeholder="you@example.com" required />

        <label>Password</label>
        <input name="password" type="password" placeholder="password" required />

        <button class="primary" type="submit">Login</button>
        <p class="hint">No account? <a href="#" id="go-register">Register here</a></p>

        <p class="msg <?= htmlspecialchars($msgClass) ?>"><?= htmlspecialchars($activeTab === 'login' ? $msg : '') ?></p>
      </form>

      <!-- REGISTER -->
      <form id="register-form" class="form <?= $activeTab === 'register' ? '' : 'hidden' ?>" method="post">
        <input type="hidden" name="action" value="register" />
        <h2>Register</h2>

        <label>Full name</label>
        <input name="full_name" type="text" placeholder="Eman Sehas" required />

        <label>Email</label>
        <input name="email" type="email" placeholder="you@example.com" required />

        <label>Password</label>
        <input name="password" type="password" placeholder="password" minlength="4" required />

        <!-- Optional: choose role (remove this if you want admin to be restricted) -->
        <label>Role</label>
        <select name="role">
          <option value="resident" selected>Resident</option>
          <option value="admin">Admin</option>
        </select>

        <button class="primary" type="submit">Register</button>
        <p class="hint">Already have an account? <a href="#" id="go-login">Login</a></p>

        <p class="msg <?= htmlspecialchars($msgClass) ?>"><?= htmlspecialchars($activeTab === 'register' ? $msg : '') ?></p>
      </form>
    </section>
  </main>

  <script>
    const loginBtn = document.getElementById('show-login');
    const regBtn = document.getElementById('show-register');
    const loginForm = document.getElementById('login-form');
    const regForm = document.getElementById('register-form');

    function showLogin(){
      loginBtn.classList.add('active');
      regBtn.classList.remove('active');
      loginForm.classList.remove('hidden');
      regForm.classList.add('hidden');
    }
    function showRegister(){
      regBtn.classList.add('active');
      loginBtn.classList.remove('active');
      regForm.classList.remove('hidden');
      loginForm.classList.add('hidden');
    }

    loginBtn.addEventListener('click', (e)=>{ e.preventDefault(); showLogin(); });
    regBtn.addEventListener('click', (e)=>{ e.preventDefault(); showRegister(); });

    document.getElementById('go-register').addEventListener('click', (e)=>{ e.preventDefault(); showRegister(); });
    document.getElementById('go-login').addEventListener('click', (e)=>{ e.preventDefault(); showLogin(); });
  </script>
</body>
</html>