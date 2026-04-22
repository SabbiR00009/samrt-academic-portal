<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'admin') { header("Location: /acadportal/auth/login.php"); exit; }

$msg  = '';
$type = '';

if (isset($_POST['add_course'])) {
    $title      = mysqli_real_escape_string($conn, $_POST['title']);
    $code       = mysqli_real_escape_string($conn, $_POST['code']);
    $teacher_id = intval($_POST['teacher_id']);

    // Check if course code already exists
    $check = mysqli_query($conn,
        "SELECT id FROM courses WHERE code='$code'");
    if (mysqli_num_rows($check) > 0) {
        $msg  = "Course code '$code' already exists!";
        $type = "error";
    } else {
        mysqli_query($conn,
            "INSERT INTO courses (title, code, teacher_id)
             VALUES ('$title', '$code', $teacher_id)");
        $msg  = "Course '$title' created and assigned to teacher!";
        $type = "success";
    }
}

if (isset($_GET['delete'])) {
    $did = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM courses WHERE id=$did");
    header("Location: /acadportal/admin/manage_courses.php?msg=deleted"); exit;
}

$teachers = mysqli_query($conn,
    "SELECT id, name FROM users WHERE role='teacher' ORDER BY name");
$courses  = mysqli_query($conn,
    "SELECT c.*, u.name AS teacher_name
     FROM courses c
     LEFT JOIN users u ON c.teacher_id = u.id
     ORDER BY c.title");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Courses — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Manage Courses</h2>

  <?php if($msg): ?>
    <div class="alert <?= $type == 'error' ? 'error' : '' ?>"><?= $msg ?></div>
  <?php endif; ?>
  <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <div class="alert">Course deleted successfully!</div>
  <?php endif; ?>

  <div class="card">
    <h3>Add New Course</h3>
    <form method="POST">
      <label>Course Title</label>
      <input type="text" name="title" placeholder="e.g. Web Programming" required>

      <label>Course Code</label>
      <input type="text" name="code" placeholder="e.g. CSE301" required>

      <label>Assign Teacher</label>
      <select name="teacher_id" required>
        <option value="">-- Select Teacher --</option>
        <?php while($t = mysqli_fetch_assoc($teachers)): ?>
          <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
        <?php endwhile; ?>
      </select>

      <button type="submit" name="add_course">Add Course</button>
    </form>
  </div>

  <div class="card">
    <h3>All Courses</h3>
    <table class="data-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Code</th>
          <th>Assigned Teacher</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php while($c = mysqli_fetch_assoc($courses)): ?>
        <tr>
          <td><?= $c['title'] ?></td>
          <td><?= $c['code'] ?></td>
          <td>
            <?php if($c['teacher_name']): ?>
              <span class="role-badge role-teacher"><?= $c['teacher_name'] ?></span>
            <?php else: ?>
              <span style="color:#e53e3e">No teacher assigned!</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="?delete=<?= $c['id'] ?>"
               onclick="return confirm('Delete this course? This will also remove all quizzes and grades!')"
               class="btn-danger">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>