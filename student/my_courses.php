<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'student') { header("Location: /acadportal/auth/login.php"); exit; }

$sid = $_SESSION['user_id'];

// If a specific course is selected, show its quizzes
if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);

    // Verify enrollment
    $check = mysqli_query($conn,
        "SELECT id FROM enrollments WHERE student_id=$sid AND course_id=$course_id");
    if (mysqli_num_rows($check) == 0) {
        header("Location: /acadportal/student/my_courses.php"); exit;
    }

    $course  = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM courses WHERE id=$course_id"));
    $quizzes = mysqli_query($conn,
        "SELECT q.*,
           (SELECT COUNT(*) FROM quiz_attempts
            WHERE quiz_id=q.id AND student_id=$sid) AS attempts,
           (SELECT MAX(score) FROM quiz_attempts
            WHERE quiz_id=q.id AND student_id=$sid) AS best_score
         FROM quizzes q WHERE q.course_id=$course_id");

    include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $course['title'] ?> — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <div style="margin-bottom:20px">
    <a href="/acadportal/student/my_courses.php"
       style="color:#718096;font-size:13px;text-decoration:none">
      &larr; Back to My Courses
    </a>
  </div>

  <h2><?= $course['title'] ?>
    <span style="font-size:15px;color:#718096;font-weight:400">(<?= $course['code'] ?>)</span>
  </h2>

  <div class="card">
    <h3>Available Quizzes</h3>
    <?php if(mysqli_num_rows($quizzes) == 0): ?>
      <p style="color:#718096;padding:10px 0">No quizzes yet for this course.</p>
    <?php else: ?>
    <table class="data-table">
      <thead>
        <tr>
          <th>Quiz Title</th><th>Time Limit</th><th>Total Marks</th>
          <th>Your Attempts</th><th>Best Score</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php while($q = mysqli_fetch_assoc($quizzes)):
          $pct = ($q['best_score'] !== null && $q['total_marks'])
                 ? round(($q['best_score'] / $q['total_marks']) * 100) : null;
      ?>
        <tr>
          <td><?= $q['title'] ?></td>
          <td><?= $q['time_limit'] ?> mins</td>
          <td><?= $q['total_marks'] ?></td>
          <td><?= $q['attempts'] ?></td>
          <td>
            <?php if($pct !== null): ?>
              <?= $q['best_score'] ?>/<?= $q['total_marks'] ?>
              <span style="color:#718096;font-size:12px">(<?= $pct ?>%)</span>
            <?php else: ?>
              <span style="color:#718096">—</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="/acadportal/student/take_quiz.php?id=<?= $q['id'] ?>"
               class="btn-primary"
               onclick="return confirm('Start quiz? Timer begins immediately!')">
              Start Quiz
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>

<?php
// Stop here — don't show the courses list
exit;
}

// ── No course_id — show ALL enrolled courses ──────────────────────────────
$courses = mysqli_query($conn,
    "SELECT c.id, c.title, c.code, u.name AS teacher_name,
            COUNT(DISTINCT q.id)  AS quiz_count,
            COUNT(DISTINCT qa.id) AS attempts_count
     FROM enrollments e
     JOIN courses c ON e.course_id = c.id
     LEFT JOIN users u    ON c.teacher_id = u.id
     LEFT JOIN quizzes q  ON q.course_id  = c.id
     LEFT JOIN quiz_attempts qa ON qa.quiz_id = q.id AND qa.student_id = $sid
     WHERE e.student_id = $sid
     GROUP BY c.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Courses — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>My Courses</h2>

  <?php if(mysqli_num_rows($courses) == 0): ?>
    <div class="card">
      <p style="color:#718096">You are not enrolled in any courses yet.
         Please contact your admin.</p>
    </div>
  <?php else: ?>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px">
    <?php while($c = mysqli_fetch_assoc($courses)): ?>
    <div class="card" style="margin-bottom:0">

      <!-- Course color bar -->
      <div style="height:6px;background:#2b6cb0;border-radius:4px;margin-bottom:16px"></div>

      <h3 style="margin-bottom:4px"><?= $c['title'] ?></h3>
      <p style="font-size:13px;color:#718096;margin-bottom:14px">
        <?= $c['code'] ?> &nbsp;|&nbsp; Teacher: <?= $c['teacher_name'] ?? 'N/A' ?>
      </p>

      <div style="display:flex;gap:16px;margin-bottom:16px">
        <div style="text-align:center">
          <div style="font-size:22px;font-weight:700;color:#2b6cb0"><?= $c['quiz_count'] ?></div>
          <div style="font-size:12px;color:#718096">Quizzes</div>
        </div>
        <div style="text-align:center">
          <div style="font-size:22px;font-weight:700;color:#2b6cb0"><?= $c['attempts_count'] ?></div>
          <div style="font-size:12px;color:#718096">Attempts</div>
        </div>
      </div>

      <a href="/acadportal/student/my_courses.php?course_id=<?= $c['id'] ?>"
         class="btn-primary"
         style="display:block;text-align:center">
        View Quizzes &rarr;
      </a>
    </div>
    <?php endwhile; ?>
  </div>

  <?php endif; ?>
</div>
</body>
</html>