<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'teacher') { header("Location: /acadportal/auth/login.php"); exit; }

$teacher_id = $_SESSION['user_id'];
$msg  = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);

    foreach ($_POST['marks'] as $student_id => $mark) {
        $student_id = intval($student_id);
        $mark       = floatval($mark);

        // GPA out of 4.0 based on marks out of 100
        if      ($mark >= 80) $gpa = 4.0;
        elseif  ($mark >= 75) $gpa = 3.75;
        elseif  ($mark >= 70) $gpa = 3.5;
        elseif  ($mark >= 65) $gpa = 3.25;
        elseif  ($mark >= 60) $gpa = 3.0;
        elseif  ($mark >= 55) $gpa = 2.75;
        elseif  ($mark >= 50) $gpa = 2.5;
        elseif  ($mark >= 45) $gpa = 2.25;
        elseif  ($mark >= 40) $gpa = 2.0;
        else                  $gpa = 0.0;

        // Get quiz average for this student in this course
        $avg_res = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT AVG(qa.score) as avg
             FROM quiz_attempts qa
             JOIN quizzes q ON qa.quiz_id = q.id
             WHERE qa.student_id = $student_id
             AND q.course_id = $course_id"));
        $quiz_avg = $avg_res['avg'] ?? 0;

        // Check if grade record already exists
        $exists = mysqli_query($conn,
            "SELECT id FROM grades
             WHERE student_id=$student_id AND course_id=$course_id");

        if (mysqli_num_rows($exists) > 0) {
            // UPDATE existing record
            $update = mysqli_query($conn,
                "UPDATE grades
                 SET manual_marks=$mark, gpa=$gpa, quiz_avg=$quiz_avg,
                     updated_at=NOW()
                 WHERE student_id=$student_id AND course_id=$course_id");
        } else {
            // INSERT new record
            $update = mysqli_query($conn,
                "INSERT INTO grades
                 (student_id, course_id, manual_marks, gpa, quiz_avg)
                 VALUES ($student_id, $course_id, $mark, $gpa, $quiz_avg)");
        }
    }

    $msg  = "Grades saved successfully!";
    $type = "success";
}

// Get courses for this teacher
$courses = mysqli_query($conn,
    "SELECT * FROM courses WHERE teacher_id=$teacher_id ORDER BY title");

$selected_course = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

// Also handle POST redirect with course_id
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    $selected_course = intval($_POST['course_id']);
}

$students = null;
if ($selected_course) {
    $students = mysqli_query($conn,
        "SELECT u.id, u.name,
                COALESCE(g.manual_marks, 0) AS current_marks,
                COALESCE(g.quiz_avg, 0)     AS quiz_avg,
                COALESCE(g.gpa, 0)          AS current_gpa
         FROM enrollments e
         JOIN users u ON e.student_id = u.id
         LEFT JOIN grades g ON g.student_id = u.id AND g.course_id = $selected_course
         WHERE e.course_id = $selected_course
         ORDER BY u.name");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enter Grades — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Enter Manual Grades</h2>

  <?php if($msg): ?>
    <div class="alert <?= $type == 'error' ? 'error' : '' ?>"><?= $msg ?></div>
  <?php endif; ?>

  <!-- Course selector -->
  <div class="card">
    <h3>Select Course</h3>
    <form method="GET">
      <label>Course</label>
      <select name="course_id" onchange="this.form.submit()" required>
        <option value="">-- Select a course --</option>
        <?php
        mysqli_data_seek($courses, 0);
        while($c = mysqli_fetch_assoc($courses)):
        ?>
          <option value="<?= $c['id'] ?>"
            <?= ($selected_course == $c['id']) ? 'selected' : '' ?>>
            <?= $c['title'] ?> (<?= $c['code'] ?>)
          </option>
        <?php endwhile; ?>
      </select>
    </form>
  </div>

  <!-- Grade entry form -->
  <?php if($selected_course && $students && mysqli_num_rows($students) > 0): ?>
  <div class="card">
    <h3>
      Students —
      <?php
        $course_info = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT title FROM courses WHERE id=$selected_course"));
        echo $course_info['title'];
      ?>
    </h3>
    <form method="POST">
      <input type="hidden" name="course_id" value="<?= $selected_course ?>">
      <table class="data-table">
        <thead>
          <tr>
            <th>Student Name</th>
            <th>Quiz Average</th>
            <th>Current GPA</th>
            <th>Manual Marks (out of 100)</th>
          </tr>
        </thead>
        <tbody>
        <?php while($s = mysqli_fetch_assoc($students)): ?>
          <tr>
            <td><?= $s['name'] ?></td>
            <td><?= round($s['quiz_avg'], 1) ?></td>
            <td><?= number_format($s['current_gpa'], 2) ?></td>
            <td>
              <input type="number"
                     name="marks[<?= $s['id'] ?>]"
                     value="<?= $s['current_marks'] ?>"
                     min="0" max="100" step="0.5"
                     style="width:100px">
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
      <button type="submit" style="margin-top:16px">Save All Grades</button>
    </form>
  </div>

  <?php elseif($selected_course): ?>
    <div class="card">
      <p style="color:#718096">No students enrolled in this course yet.</p>
    </div>
  <?php endif; ?>

</div>
</body>
</html>