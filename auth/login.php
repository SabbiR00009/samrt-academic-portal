<?php
session_start();
include '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass  = MD5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$pass'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role'];

        if ($user['role'] == 'admin')   { header("Location: ../admin/dashboard.php");   exit; }
        if ($user['role'] == 'teacher') { header("Location: ../teacher/dashboard.php"); exit; }
        if ($user['role'] == 'student') { header("Location: ../student/dashboard.php"); exit; }
    } else {
        $error = "Wrong email or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Smart Academic Portal</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #1a1f36;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-wrapper {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-box {
      background: #fff;
      border-radius: 14px;
      padding: 44px 40px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .login-logo {
      text-align: center;
      margin-bottom: 28px;
    }

    .login-logo h1 {
      font-size: 22px;
      font-weight: 700;
      color: #1a1f36;
      letter-spacing: 0.5px;
    }

    .login-logo p {
      font-size: 13px;
      color: #718096;
      margin-top: 4px;
    }

    .error-box {
      background: #fed7d7;
      color: #742a2a;
      border: 1px solid #fc8181;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      margin-bottom: 20px;
      text-align: center;
    }

    label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #4a5568;
      margin-bottom: 6px;
      margin-top: 16px;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid #cbd5e0;
      border-radius: 8px;
      font-size: 14px;
      background: #f7fafc;
      color: #333;
      transition: border 0.2s, background 0.2s;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #4299e1;
      background: #fff;
    }

    .login-btn {
      width: 100%;
      margin-top: 24px;
      padding: 12px;
      background: #2b6cb0;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
      letter-spacing: 0.3px;
    }

    .login-btn:hover {
      background: #2c5282;
    }

    .login-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 12px;
      color: #a0aec0;
    }

    .demo-accounts {
      margin-top: 24px;
      border-top: 1px solid #e2e8f0;
      padding-top: 18px;
    }

    .demo-accounts p {
      font-size: 12px;
      color: #718096;
      margin-bottom: 10px;
      text-align: center;
    }

    .demo-btn {
      display: block;
      width: 100%;
      padding: 8px;
      margin-bottom: 6px;
      border: 1px solid #e2e8f0;
      border-radius: 7px;
      background: #f7fafc;
      font-size: 12px;
      color: #4a5568;
      cursor: pointer;
      text-align: left;
      transition: background 0.15s;
    }

    .demo-btn:hover { background: #edf2f7; }

    .demo-btn span {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 99px;
      font-size: 11px;
      font-weight: 500;
      margin-right: 8px;
    }

    .badge-admin   { background: #fff5f5; color: #c53030; }
    .badge-teacher { background: #f0fff4; color: #276749; }
    .badge-student { background: #ebf8ff; color: #2b6cb0; }
  </style>
</head>
<body>

<div class="login-wrapper">
  <div class="login-box">

    <div class="login-logo">
      <h1>Smart Academic Portal</h1>
      <p>Quiz & Grade Management System</p>
    </div>

    <?php if($error): ?>
      <div class="error-box"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Email Address</label>
      <input type="email" name="email" placeholder="Enter your email" required
             value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">

      <label>Password</label>
      <input type="password" name="password" placeholder="Enter your password" required>

      <button type="submit" class="login-btn">Login</button>
    </form>

    <!-- Demo accounts for testing -->
    <div class="demo-accounts">
      <p>Quick login for testing:</p>
      <button class="demo-btn" onclick="fillLogin('admin@acad.com','admin123')">
        <span class="badge-admin">Admin</span> admin@acad.com / admin123
      </button>
      <button class="demo-btn" onclick="fillLogin('teacher@acad.com','teach123')">
        <span class="badge-teacher">Teacher</span> teacher@acad.com / teach123
      </button>
      <button class="demo-btn" onclick="fillLogin('student@acad.com','pass123')">
        <span class="badge-student">Student</span> student@acad.com / pass123
      </button>
    </div>

    <div class="login-footer">
      Smart Academic Portal &copy; <?= date('Y') ?>
    </div>

  </div>
</div>

<script>
  function fillLogin(email, password) {
    document.querySelector('input[name="email"]').value    = email;
    document.querySelector('input[name="password"]').value = password;
  }
</script>

</body>
</html>