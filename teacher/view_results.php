<?php
// teacher/view_results.php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SESSION['role'] != 'teacher') { header("Location: ../auth/login.php"); exit; }

$teacher_id = $_SESSION['user_id'];

$results = mysqli_query($conn,
    "SELECT u.name AS student_name, q.title AS quiz_title,
            c.title AS course_title, qa.score, q.total_marks, qa.attempted_at
     FROM quiz_attempts qa
     JOIN users u   ON qa.student_id = u.id
     JOIN quizzes q ON qa.quiz_id    = q.id
     JOIN courses c ON q.course_id   = c.id
     WHERE c.teacher_id = $teacher_id
     ORDER BY qa.attempted_at DESC");
?>
<!DOCTYPE html><html>
<head><title>View Results</title><link rel="stylesheet" href="../assets/style.css"></head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>Student Quiz Results</h2>
  <table class="data-table">
    <thead>
      <tr>
        <th>Student</th><th>Quiz</th><th>Course</th>
        <th>Score</th><th>Date</th>
      </tr>
    </thead>
    <tbody>
    <?php while($r = mysqli_fetch_assoc($results)): ?>
      <tr>
        <td><?= $r['student_name'] ?></td>
        <td><?= $r['quiz_title'] ?></td>
        <td><?= $r['course_title'] ?></td>
        <td><?= $r['score'] ?> / <?= $r['total_marks'] ?></td>
        <td><?= date('d M Y', strtotime($r['attempted_at'])) ?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body></html>