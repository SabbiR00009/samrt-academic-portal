<?php
// teacher/enter_grades.php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SESSION['role'] != 'teacher') { header("Location: ../auth/login.php"); exit; }

$teacher_id = $_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    foreach ($_POST['marks'] as $student_id => $mark) {
        $mark = floatval($mark);
        // Simple GPA: marks out of 100, GPA out of 4.0
        $gpa  = round(($mark / 100) * 4.0, 2);
        mysqli_query($conn,
            "INSERT INTO grades (student_id, course_id, manual_marks, gpa)
             VALUES ($student_id, $course_id, $mark, $gpa)
             ON DUPLICATE KEY UPDATE manual_marks=$mark, gpa=$gpa");
    }
    $msg = "Grades saved successfully!";
}

// Get courses taught by this teacher
$courses = mysqli_query($conn,
    "SELECT * FROM courses WHERE teacher_id=$teacher_id");

$selected_course = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$students = [];
if ($selected_course) {
    $students = mysqli_query($conn,
        "SELECT u.id, u.name,
                COALESCE(g.manual_marks, 0) AS current_marks,
                COALESCE(g.quiz_avg, 0)     AS quiz_avg
         FROM enrollments e
         JOIN users u ON e.student_id = u.id
         LEFT JOIN grades g ON g.student_id=u.id AND g.course_id=$selected_course
         WHERE e.course_id=$selected_course");
}
?>
<!DOCTYPE html><html>
<head><title>Enter Grades</title><link rel="stylesheet" href="../assets/style.css"></head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>Enter Manual Grades</h2>
  <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

  <form method="GET">
    <label>Select Course</label>
    <select name="course_id" onchange="this.form.submit()">
      <option value="">-- Select --</option>
      <?php while($c = mysqli_fetch_assoc($courses)): ?>
        <option value="<?= $c['id'] ?>"
          <?= ($selected_course == $c['id']) ? 'selected' : '' ?>>
          <?= $c['title'] ?>
        </option>
      <?php endwhile; ?>
    </select>
  </form>

  <?php if($selected_course && $students): ?>
  <form method="POST">
    <input type="hidden" name="course_id" value="<?= $selected_course ?>">
    <table class="data-table">
      <thead>
        <tr><th>Student</th><th>Quiz Avg</th><th>Manual Marks (out of 100)</th></tr>
      </thead>
      <tbody>
      <?php while($s = mysqli_fetch_assoc($students)): ?>
        <tr>
          <td><?= $s['name'] ?></td>
          <td><?= round($s['quiz_avg'], 1) ?></td>
          <td>
            <input type="number" name="marks[<?= $s['id'] ?>]"
                   value="<?= $s['current_marks'] ?>" min="0" max="100" step="0.5">
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <button type="submit">Save Grades</button>
  </form>
  <?php endif; ?>
</div>
</body></html>