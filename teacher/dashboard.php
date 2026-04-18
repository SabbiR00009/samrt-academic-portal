<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'teacher') { header("Location: /acadportal/auth/login.php"); exit; }

$tid = $_SESSION['user_id'];

$total_courses = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM courses WHERE teacher_id=$tid"))['c'];

$total_quizzes = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM quizzes q
     JOIN courses c ON q.course_id=c.id
     WHERE c.teacher_id=$tid"))['c'];

$total_students = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(DISTINCT e.student_id) as c FROM enrollments e
     JOIN courses c ON e.course_id=c.id
     WHERE c.teacher_id=$tid"))['c'];

$total_attempts = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as c FROM quiz_attempts qa
     JOIN quizzes q ON qa.quiz_id=q.id
     JOIN courses c ON q.course_id=c.id
     WHERE c.teacher_id=$tid"))['c'];

$courses = mysqli_query($conn,
    "SELECT c.*,
       (SELECT COUNT(*) FROM enrollments WHERE course_id=c.id) AS enrolled,
       (SELECT COUNT(*) FROM quizzes WHERE course_id=c.id) AS quiz_count
     FROM courses c WHERE c.teacher_id=$tid");

$recent_attempts = mysqli_query($conn,
    "SELECT u.name AS student_name, q.title AS quiz_title,
            qa.score, q.total_marks, qa.attempted_at
     FROM quiz_attempts qa
     JOIN users u   ON qa.student_id = u.id
     JOIN quizzes q ON qa.quiz_id    = q.id
     JOIN courses c ON q.course_id   = c.id
     WHERE c.teacher_id = $tid
     ORDER BY qa.attempted_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Dashboard — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Welcome, <?= $_SESSION['user_name'] ?> 👋</h2>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number"><?= $total_courses ?></div>
      <div class="stat-label">My Courses</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $total_quizzes ?></div>
      <div class="stat-label">Quizzes Created</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $total_students ?></div>
      <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?= $total_attempts ?></div>
      <div class="stat-label">Quiz Attempts</div>
    </div>
  </div>

  <!-- My Courses -->
  <div class="card">
    <h3>My Courses</h3>
    <?php if(mysqli_num_rows($courses) == 0): ?>
      <p style="color:#718096">No courses assigned to you yet. Contact admin.</p>
    <?php else: ?>
    <table class="data-table">
      <thead>
        <tr>
          <th>Course Title</th>
          <th>Code</th>
          <th>Students Enrolled</th>
          <th>Quizzes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php while($c = mysqli_fetch_assoc($courses)): ?>
        <tr>
          <td><?= $c['title'] ?></td>
          <td><?= $c['code'] ?></td>
          <td><?= $c['enrolled'] ?></td>
          <td><?= $c['quiz_count'] ?></td>
          <td style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="/acadportal/teacher/create_quiz.php" class="btn-primary">Add Quiz</a>
            <a href="/acadportal/teacher/view_results.php" class="btn-primary">Results</a>
            <a href="/acadportal/teacher/enter_grades.php?course_id=<?= $c['id'] ?>"
               class="btn-primary">Grades</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- Recent Attempts -->
  <div class="card">
    <h3>Recent Student Attempts</h3>
    <?php if(mysqli_num_rows($recent_attempts) == 0): ?>
      <p style="color:#718096">No quiz attempts yet from your students.</p>
    <?php else: ?>
    <table class="data-table">
      <thead>
        <tr>
          <th>Student</th>
          <th>Quiz</th>
          <th>Score</th>
          <th>Percentage</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
      <?php while($a = mysqli_fetch_assoc($recent_attempts)):
          $pct = round(($a['score'] / $a['total_marks']) * 100);
          $color = $pct >= 80 ? '#276749' : ($pct >= 50 ? '#744210' : '#742a2a');
      ?>
        <tr>
          <td><?= $a['student_name'] ?></td>
          <td><?= $a['quiz_title'] ?></td>
          <td><?= $a['score'] ?> / <?= $a['total_marks'] ?></td>
          <td>
            <span style="color:<?= $color ?>;font-weight:500"><?= $pct ?>%</span>
            <div style="background:#e2e8f0;border-radius:99px;height:6px;margin-top:4px;width:80px">
              <div style="background:<?= $color ?>;width:<?= min($pct,100) ?>%;height:6px;border-radius:99px"></div>
            </div>
          </td>
          <td><?= date('d M Y', strtotime($a['attempted_at'])) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- Quick Actions -->
  <div class="card">
    <h3>Quick Actions</h3>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
      <a href="/acadportal/teacher/create_quiz.php"  class="btn-primary">Create New Quiz</a>
      <a href="/acadportal/teacher/view_results.php" class="btn-primary">View All Results</a>
      <a href="/acadportal/teacher/enter_grades.php" class="btn-primary">Enter Grades</a>
    </div>
  </div>

</div>
</body>
</html>