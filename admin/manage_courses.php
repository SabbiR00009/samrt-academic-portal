<?php
// admin/manage_courses.php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }

$msg = '';

if (isset($_POST['add_course'])) {
    $title      = $_POST['title'];
    $code       = $_POST['code'];
    $teacher_id = $_POST['teacher_id'];
    mysqli_query($conn,
        "INSERT INTO courses (title, code, teacher_id) VALUES ('$title','$code',$teacher_id)");
    $msg = "Course '$title' created!";
}

if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM courses WHERE id=" . intval($_GET['delete']));
    header("Location: manage_courses.php"); exit;
}

$teachers = mysqli_query($conn, "SELECT id, name FROM users WHERE role='teacher'");
$courses  = mysqli_query($conn,
    "SELECT c.*, u.name AS teacher_name FROM courses c
     LEFT JOIN users u ON c.teacher_id = u.id ORDER BY c.title");
?>
<!DOCTYPE html><html>
<head><title>Manage Courses</title><link rel="stylesheet" href="../assets/style.css"></head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>Manage Courses</h2>
  <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

  <div class="card">
    <h3>Add New Course</h3>
    <form method="POST">
      <label>Course Title</label>
      <input type="text" name="title" placeholder="e.g. Web Programming" required>
      <label>Course Code</label>
      <input type="text" name="code" placeholder="e.g. CSE301" required>
      <label>Assign Teacher</label>
      <select name="teacher_id" required>
        <?php while($t = mysqli_fetch_assoc($teachers)): ?>
          <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
        <?php endwhile; ?>
      </select>
      <button type="submit" name="add_course">Add Course</button>
    </form>
  </div>

  <h3>All Courses</h3>
  <table class="data-table">
    <thead><tr><th>Title</th><th>Code</th><th>Teacher</th><th>Action</th></tr></thead>
    <tbody>
    <?php while($c = mysqli_fetch_assoc($courses)): ?>
      <tr>
        <td><?= $c['title'] ?></td>
        <td><?= $c['code'] ?></td>
        <td><?= $c['teacher_name'] ?? 'Unassigned' ?></td>
        <td><a href="?delete=<?= $c['id'] ?>" onclick="return confirm('Delete course?')" class="btn-danger">Delete</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body></html>