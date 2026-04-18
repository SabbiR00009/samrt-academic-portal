<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'admin') { header("Location: /acadportal/auth/login.php"); exit; }

$total_students = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM users WHERE role='student'"))['c'];
$total_teachers = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM users WHERE role='teacher'"))['c'];
$total_courses  = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM courses"))['c'];
$total_attempts = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM quiz_attempts"))['c'];

$recent_users = mysqli_query($conn,
    "SELECT name, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$recent_attempts = mysqli_query($conn,
    "SELECT u.name, q.title, qa.score, q.total_marks, qa.attempted_at
     FROM quiz_attempts qa
     JOIN users u ON qa.student_id = u.id
     JOIN quizzes q ON qa.quiz_id = q.id
     ORDER BY qa.attempted_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Admin Dashboard</h2>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number"><?= $total_students ?></div>
      <div class="stat-label">Students</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $total_teachers ?></div>
      <div class="stat-label">Teachers</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $total_courses ?></div>
      <div class="stat-label">Courses</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $total_attempts ?></div>
      <div class="stat-label">Quiz Attempts</div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    <div class="card">
      <h3>Recent Users</h3>
      <table class="data-table">
        <thead>
          <tr><th>Name</th><th>Role</th><th>Joined</th></tr>
        </thead>
        <tbody>
        <?php while($u = mysqli_fetch_assoc($recent_users)): ?>
          <tr>
            <td><?= $u['name'] ?></td>
            <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="card">
      <h3>Recent Quiz Attempts</h3>
      <table class="data-table">
        <thead>
          <tr><th>Student</th><th>Quiz</th><th>Score</th></tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($recent_attempts) == 0): ?>
          <tr><td colspan="3" style="color:#718096">No attempts yet.</td></tr>
        <?php else: ?>
        <?php while($a = mysqli_fetch_assoc($recent_attempts)): ?>
          <tr>
            <td><?= $a['name'] ?></td>
            <td><?= $a['title'] ?></td>
            <td><?= $a['score'] ?>/<?= $a['total_marks'] ?></td>
          </tr>
        <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

  <div style="display:flex;gap:12px;margin-top:20px;flex-wrap:wrap">
    <a href="/acadportal/admin/manage_users.php"   class="btn-primary">Manage Users</a>
    <a href="/acadportal/admin/manage_courses.php" class="btn-primary">Manage Courses</a>
    <a href="/acadportal/admin/enroll.php"         class="btn-primary">Enroll Students</a>
  </div>

</div>
</body>
</html>