<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'admin') { header("Location: /acadportal/auth/login.php"); exit; }

$msg  = '';
$type = '';

// Add new user
if (isset($_POST['add_user'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = MD5($_POST['password']);
    $role  = $_POST['role'];

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $msg  = "Email already exists!";
        $type = "error";
    } else {
        mysqli_query($conn,
            "INSERT INTO users (name, email, password, role)
             VALUES ('$name', '$email', '$pass', '$role')");
        $msg  = "User '$name' added successfully!";
        $type = "success";
    }
}

// Delete user
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    if ($del_id == $_SESSION['user_id']) {
        $msg  = "You cannot delete your own account!";
        $type = "error";
    } else {
        mysqli_query($conn, "DELETE FROM users WHERE id=$del_id");
        header("Location: /acadportal/admin/manage_users.php?msg=deleted"); exit;
    }
}

// Filter by role
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$where = "WHERE 1=1";
if ($filter != 'all') $where .= " AND role='$filter'";
if ($search != '')    $where .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";

$users = mysqli_query($conn,
    "SELECT * FROM users $where ORDER BY role, name");

// Count by role
$count_all      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
$count_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='student'"))['c'];
$count_teachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='teacher'"))['c'];
$count_admins   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='admin'"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Manage Users</h2>

  <?php if($msg): ?>
    <div class="alert <?= $type == 'error' ? 'error' : '' ?>"><?= $msg ?></div>
  <?php endif; ?>
  <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <div class="alert">User deleted successfully!</div>
  <?php endif; ?>

  <!-- Add User Form -->
  <div class="card">
    <h3>Add New User</h3>
    <form method="POST">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
          <label>Full Name</label>
          <input type="text" name="name" placeholder="e.g. John Doe" required>
        </div>
        <div>
          <label>Email Address</label>
          <input type="email" name="email" placeholder="e.g. john@acad.com" required>
        </div>
        <div>
          <label>Password</label>
          <input type="password" name="password" placeholder="Min 6 characters" required>
        </div>
        <div>
          <label>Role</label>
          <select name="role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>
      <button type="submit" name="add_user" style="margin-top:16px">Add User</button>
    </form>
  </div>

  <!-- Filter tabs -->
  <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;align-items:center">
    <a href="?filter=all"
       style="padding:7px 16px;border-radius:7px;text-decoration:none;font-size:13px;font-weight:500;
              background:<?= $filter=='all' ? '#2b6cb0' : '#e2e8f0' ?>;
              color:<?= $filter=='all' ? '#fff' : '#4a5568' ?>">
      All (<?= $count_all ?>)
    </a>
    <a href="?filter=student"
       style="padding:7px 16px;border-radius:7px;text-decoration:none;font-size:13px;font-weight:500;
              background:<?= $filter=='student' ? '#2b6cb0' : '#e2e8f0' ?>;
              color:<?= $filter=='student' ? '#fff' : '#4a5568' ?>">
      Students (<?= $count_students ?>)
    </a>
    <a href="?filter=teacher"
       style="padding:7px 16px;border-radius:7px;text-decoration:none;font-size:13px;font-weight:500;
              background:<?= $filter=='teacher' ? '#2b6cb0' : '#e2e8f0' ?>;
              color:<?= $filter=='teacher' ? '#fff' : '#4a5568' ?>">
      Teachers (<?= $count_teachers ?>)
    </a>
    <a href="?filter=admin"
       style="padding:7px 16px;border-radius:7px;text-decoration:none;font-size:13px;font-weight:500;
              background:<?= $filter=='admin' ? '#2b6cb0' : '#e2e8f0' ?>;
              color:<?= $filter=='admin' ? '#fff' : '#4a5568' ?>">
      Admins (<?= $count_admins ?>)
    </a>

    <!-- Search box -->
    <form method="GET" style="margin-left:auto;display:flex;gap:8px">
      <input type="hidden" name="filter" value="<?= $filter ?>">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
             placeholder="Search name or email..."
             style="padding:7px 12px;border:1px solid #cbd5e0;border-radius:7px;
                    font-size:13px;width:220px">
      <button type="submit"
              style="padding:7px 14px;background:#2b6cb0;color:#fff;border:none;
                     border-radius:7px;font-size:13px;cursor:pointer">
        Search
      </button>
      <?php if($search): ?>
        <a href="?filter=<?= $filter ?>"
           style="padding:7px 14px;background:#e2e8f0;color:#4a5568;border-radius:7px;
                  font-size:13px;text-decoration:none">
          Clear
        </a>
      <?php endif; ?>
    </form>
  </div>

  <!-- Users Table -->
  <table class="data-table">
    <thead>
      <tr>
        <th style="width:60px">ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Joined</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php if(mysqli_num_rows($users) == 0): ?>
      <tr>
        <td colspan="6" style="text-align:center;color:#718096;padding:24px">
          No users found.
        </td>
      </tr>
    <?php endif; ?>
    <?php while($u = mysqli_fetch_assoc($users)): ?>
      <tr>
        <td>
          <span style="background:#edf2f7;color:#4a5568;padding:3px 8px;
                       border-radius:5px;font-size:12px;font-weight:600">
            #<?= $u['id'] ?>
          </span>
        </td>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:32px;height:32px;border-radius:50%;
                        background:<?= $u['role']=='student' ? '#bee3f8' : ($u['role']=='teacher' ? '#c6f6d5' : '#fed7d7') ?>;
                        color:<?= $u['role']=='student' ? '#2b6cb0' : ($u['role']=='teacher' ? '#276749' : '#c53030') ?>;
                        display:flex;align-items:center;justify-content:center;
                        font-weight:700;font-size:13px">
              <?= strtoupper(substr($u['name'], 0, 1)) ?>
            </div>
            <span style="font-weight:500"><?= $u['name'] ?></span>
          </div>
        </td>
        <td style="color:#718096"><?= $u['email'] ?></td>
        <td>
          <span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span>
        </td>
        <td style="font-size:13px;color:#718096">
          <?= date('d M Y', strtotime($u['created_at'])) ?>
        </td>
        <td>
          <?php if($u['id'] != $_SESSION['user_id']): ?>
            <a href="?delete=<?= $u['id'] ?>&filter=<?= $filter ?>"
               onclick="return confirm('Delete <?= $u['name'] ?>? This cannot be undone!')"
               class="btn-danger">Delete</a>
          <?php else: ?>
            <span style="color:#a0aec0;font-size:12px">You</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>

  <p style="font-size:12px;color:#a0aec0;margin-top:8px">
    Showing <?= mysqli_num_rows($users) ?> user(s)
  </p>

</div>
</body>
</html>