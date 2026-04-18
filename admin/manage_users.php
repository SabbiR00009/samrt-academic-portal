<?php
// admin/manage_users.php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }

$msg = '';

// Add new user
if (isset($_POST['add_user'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = MD5($_POST['password']);
    $role  = $_POST['role'];
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "Email already exists!";
    } else {
        mysqli_query($conn,
            "INSERT INTO users (name, email, password, role) VALUES ('$name','$email','$pass','$role')");
        $msg = "User '$name' added!";
    }
}

// Delete user
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$del_id");
    header("Location: manage_users.php?msg=deleted"); exit;
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY role, name");
?>
<!DOCTYPE html><html>
<head><title>Manage Users</title><link rel="stylesheet" href="../assets/style.css"></head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>Manage Users</h2>
  <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

  <div class="card">
    <h3>Add New User</h3>
    <form method="POST">
      <label>Full Name</label>
      <input type="text" name="name" required>
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <label>Role</label>
      <select name="role">
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
        <option value="admin">Admin</option>
      </select>
      <button type="submit" name="add_user">Add User</button>
    </form>
  </div>

  <h3>All Users</h3>
  <table class="data-table">
    <thead>
      <tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
    </thead>
    <tbody>
    <?php while($u = mysqli_fetch_assoc($users)): ?>
      <tr>
        <td><?= $u['name'] ?></td>
        <td><?= $u['email'] ?></td>
        <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
        <td>
          <?php if($u['id'] != $_SESSION['user_id']): ?>
            <a href="?delete=<?= $u['id'] ?>"
               onclick="return confirm('Delete this user?')"
               class="btn-danger">Delete</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body></html>