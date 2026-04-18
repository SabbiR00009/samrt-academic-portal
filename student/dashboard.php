<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SESSION['role'] != 'student') { header("Location: ../auth/login.php"); exit; }

$sid = $_SESSION['user_id'];

// Count enrolled courses
$total_courses = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM enrollments WHERE student_id=$sid"))['c'];

// Count quizzes taken
$total_quizzes = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM quiz_attempts WHERE student_id=$sid"))['c'];

// Overall GPA average
$gpa_row = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT AVG(gpa) as g FROM grades WHERE student_id=$sid"));
$avg_gpa = $gpa_row['g'] ? round($gpa_row['g'], 2) : 0;

// Enrolled courses with available quizzes
$courses = mysqli_query($conn,
    "SELECT c.id, c.title, c.code, u.name AS teacher_name,
            COUNT(q.id) AS quiz_count
     FROM enrollments e
     JOIN courses c ON e.course_id = c.id
     LEFT JOIN users u ON c.teacher_id = u.id
     LEFT JOIN quizzes q ON q.course_id = c.id
     WHERE e.student_id = $sid
     GROUP BY c.id");

// Recent quiz attempts
$recent = mysqli_query($conn,
    "SELECT qa.score, q.title, q.total_marks, qa.attempted_at
     FROM quiz_attempts qa
     JOIN quizzes q ON qa.quiz_id = q.id
     WHERE qa.student_id = $sid
     ORDER BY qa.attempted_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Welcome, <?= $_SESSION['user_name'] ?> 👋</h2>

  <!-- Stat cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number"><?= $total_courses ?></div>
      <div class="stat-label">Enrolled Courses</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $total_quizzes ?></div>
      <div class="stat-label">Quizzes Taken</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $avg_gpa ?></div>
      <div class="stat-label">Average GPA</div>
    </div>
  </div>

  <!-- My courses -->
  <div class="card">
    <h3>My Courses</h3>
    <?php if(mysqli_num_rows($courses) == 0): ?>
      <p style="color:#718096">You are not enrolled in any courses yet.</p>
    <?php else: ?>
    <table class="data-table">
      <thead>
        <tr><th>Course</th><th>Code</th><th>Teacher</th><th>Quizzes</th><th>Action</th></tr>
      </thead>
      <tbody>
      <?php while($c = mysqli_fetch_assoc($courses)): ?>
        <tr>
          <td><?= $c['title'] ?></td>
          <td><?= $c['code'] ?></td>
          <td><?= $c['teacher_name'] ?? 'N/A' ?></td>
          <td><?= $c['quiz_count'] ?> available</td>
          <td>
            <a href="my_courses.php?course_id=<?= $c['id'] ?>" class="btn-primary">View Quizzes</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- Recent attempts -->
  <div class="card">
    <h3>Recent Quiz Attempts</h3>
    <?php if(mysqli_num_rows($recent) == 0): ?>
      <p style="color:#718096">No quiz attempts yet. Go take a quiz!</p>
    <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Quiz</th><th>Score</th><th>Date</th></tr></thead>
      <tbody>
      <?php while($r = mysqli_fetch_assoc($recent)): ?>
        <tr>
          <td><?= $r['title'] ?></td>
          <td>
            <?= $r['score'] ?> / <?= $r['total_marks'] ?>
            <span style="color:#718096;font-size:12px">
              (<?= round(($r['score']/$r['total_marks'])*100) ?>%)
            </span>
          </td>
          <td><?= date('d M Y', strtotime($r['attempted_at'])) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>