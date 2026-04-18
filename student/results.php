<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SESSION['role'] != 'student') { header("Location: ../auth/login.php"); exit; }

$sid = $_SESSION['user_id'];

// Show flash score if redirected from submit
$flash_score = isset($_GET['score']) ? intval($_GET['score']) : null;

$attempts = mysqli_query($conn,
    "SELECT qa.score, qa.attempted_at,
            q.title AS quiz_title, q.total_marks,
            c.title AS course_title
     FROM quiz_attempts qa
     JOIN quizzes q  ON qa.quiz_id    = q.id
     JOIN courses c  ON q.course_id   = c.id
     WHERE qa.student_id = $sid
     ORDER BY qa.attempted_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Results</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>My Quiz Results</h2>

  <?php if($flash_score !== null): ?>
    <div class="alert">
      Quiz submitted! Your score: <strong><?= $flash_score ?></strong>
    </div>
  <?php endif; ?>

  <table class="data-table">
    <thead>
      <tr><th>Quiz</th><th>Course</th><th>Score</th><th>Percentage</th><th>Date</th></tr>
    </thead>
    <tbody>
    <?php while($a = mysqli_fetch_assoc($attempts)):
        $pct = round(($a['score'] / $a['total_marks']) * 100);
        $color = $pct >= 80 ? '#276749' : ($pct >= 50 ? '#744210' : '#742a2a');
    ?>
      <tr>
        <td><?= $a['quiz_title'] ?></td>
        <td><?= $a['course_title'] ?></td>
        <td><?= $a['score'] ?> / <?= $a['total_marks'] ?></td>
        <td>
          <span style="color:<?= $color ?>;font-weight:500"><?= $pct ?>%</span>
          <div style="background:#e2e8f0;border-radius:99px;height:6px;margin-top:4px;width:100px">
            <div style="background:<?= $color ?>;width:<?= $pct ?>%;height:6px;border-radius:99px"></div>
          </div>
        </td>
        <td><?= date('d M Y, h:i A', strtotime($a['attempted_at'])) ?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>