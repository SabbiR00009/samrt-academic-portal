<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SESSION['role'] != 'student') { header("Location: ../auth/login.php"); exit; }

$sid    = $_SESSION['user_id'];
$grades = mysqli_query($conn,
    "SELECT g.*, c.title AS course_title
     FROM grades g JOIN courses c ON g.course_id = c.id
     WHERE g.student_id = $sid");

$labels = []; $quiz_avgs = []; $manual = []; $gpas = [];
$rows   = [];
while ($g = mysqli_fetch_assoc($grades)) {
    $labels[]    = $g['course_title'];
    $quiz_avgs[] = round($g['quiz_avg'], 1);
    $manual[]    = round($g['manual_marks'], 1);
    $gpas[]      = round($g['gpa'], 2);
    $rows[]      = $g;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Grades</title>
  <link rel="stylesheet" href="../assets/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>My Grades & GPA</h2>

  <?php if(empty($rows)): ?>
    <div class="card">
      <p style="color:#718096">No grades recorded yet. Complete a quiz to see your grades here.</p>
    </div>
  <?php else: ?>

  <!-- Chart -->
  <div class="card">
    <h3>Performance Chart</h3>
    <canvas id="gradeChart" height="100"></canvas>
  </div>

  <!-- Grade table -->
  <div class="card">
    <h3>Grade Details</h3>
    <table class="data-table">
      <thead>
        <tr><th>Course</th><th>Quiz Avg</th><th>Manual Marks</th><th>GPA</th><th>Grade</th></tr>
      </thead>
      <tbody>
      <?php foreach($rows as $g):
          $gpa = round($g['gpa'], 2);
          if ($gpa >= 3.75)     { $letter = 'A+'; $col = '#276749'; }
          elseif ($gpa >= 3.5)  { $letter = 'A';  $col = '#276749'; }
          elseif ($gpa >= 3.0)  { $letter = 'B+'; $col = '#2b6cb0'; }
          elseif ($gpa >= 2.5)  { $letter = 'B';  $col = '#2b6cb0'; }
          elseif ($gpa >= 2.0)  { $letter = 'C';  $col = '#744210'; }
          else                  { $letter = 'F';  $col = '#742a2a'; }
      ?>
        <tr>
          <td><?= $g['course_title'] ?></td>
          <td><?= round($g['quiz_avg'], 1) ?></td>
          <td><?= round($g['manual_marks'], 1) ?></td>
          <td><?= $gpa ?></td>
          <td><strong style="color:<?= $col ?>"><?= $letter ?></strong></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script>
    new Chart(document.getElementById('gradeChart'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
          {
            label: 'Quiz Average',
            data: <?= json_encode($quiz_avgs) ?>,
            backgroundColor: '#4299e1'
          },
          {
            label: 'Manual Marks',
            data: <?= json_encode($manual) ?>,
            backgroundColor: '#68d391'
          }
        ]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>
  <?php endif; ?>
</div>
</body>
</html>