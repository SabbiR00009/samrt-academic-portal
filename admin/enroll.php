<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'admin') { header("Location: /acadportal/auth/login.php"); exit; }

$msg = '';

if (isset($_POST['enroll'])) {
    $sid = $_POST['student_id'];
    $cid = $_POST['course_id'];
    $exists = mysqli_query($conn,
        "SELECT id FROM enrollments WHERE student_id=$sid AND course_id=$cid");
    if (mysqli_num_rows($exists) > 0) {
        $msg = "error|Student is already enrolled in this course!";
    } else {
        mysqli_query($conn,
            "INSERT INTO enrollments (student_id, course_id) VALUES ($sid, $cid)");
        $msg = "success|Student enrolled successfully!";
    }
}

// Delete enrollment
if (isset($_GET['remove'])) {
    mysqli_query($conn, "DELETE FROM enrollments WHERE id=" . intval($_GET['remove']));
    header("Location: /acadportal/admin/enroll.php?msg=removed"); exit;
}

$students = mysqli_query($conn,
    "SELECT id, name FROM users WHERE role='student' ORDER BY name");
$courses  = mysqli_query($conn,
    "SELECT id, title, code FROM courses ORDER BY title");

// Fixed query — removed e.enrolled_at
$enrolled = mysqli_query($conn,
    "SELECT e.id, u.name AS sname, c.title AS ctitle, c.code
     FROM enrollments e
     JOIN users u   ON e.student_id = u.id
     JOIN courses c ON e.course_id  = c.id
     ORDER BY u.name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enroll Students — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Enroll Students</h2>

  <?php if($msg):
    $parts = explode('|', $msg);
    $type  = $parts[0];
    $text  = $parts[1];
  ?>
    <div class="alert <?= $type == 'error' ? 'error' : '' ?>"><?= $text ?></div>
  <?php endif; ?>

  <?php if(isset($_GET['msg']) && $_GET['msg'] == 'removed'): ?>
    <div class="alert">Enrollment removed successfully!</div>
  <?php endif; ?>

  <!-- Enroll Form -->
  <div class="card">
    <h3>Add New Enrollment</h3>
    <form method="POST">
      <label>Select Student</label>
      <select name="student_id" required>
        <option value="">-- Select Student --</option>
        <?php while($s = mysqli_fetch_assoc($students)): ?>
          <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
        <?php endwhile; ?>
      </select>

      <label>Select Course</label>
      <select name="course_id" required>
        <option value="">-- Select Course --</option>
        <?php while($c = mysqli_fetch_assoc($courses)): ?>
          <option value="<?= $c['id'] ?>"><?= $c['title'] ?> (<?= $c['code'] ?>)</option>
        <?php endwhile; ?>
      </select>

      <button type="submit" name="enroll">Enroll Student</button>
    </form>
  </div>

  <!-- Enrolled List -->
  <div class="card">
    <h3>All Enrollments</h3>
    <?php if(mysqli_num_rows($enrolled) == 0): ?>
      <p style="color:#718096">No enrollments yet.</p>
    <?php else: ?>
    <table class="data-table">
      <thead>
        <tr>
          <th>Student</th>
          <th>Course</th>
          <th>Course Code</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php while($e = mysqli_fetch_assoc($enrolled)): ?>
        <tr>
          <td><?= $e['sname'] ?></td>
          <td><?= $e['ctitle'] ?></td>
          <td><?= $e['code'] ?></td>
          <td>
            <a href="?remove=<?= $e['id'] ?>"
               onclick="return confirm('Remove this enrollment?')"
               class="btn-danger">Remove</a>
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