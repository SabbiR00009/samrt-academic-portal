<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<style>
.navbar {
  background: #1a1f36;
  padding: 14px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 100;
}
.nav-brand {
  color: #fff;
  font-size: 20px;
  font-weight: 700;
  letter-spacing: 1px;
  text-decoration: none;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 6px;
}
.nav-links a {
  color: #b0b8d1;
  text-decoration: none;
  font-size: 14px;
  padding: 7px 14px;
  border-radius: 7px;
  transition: all 0.2s;
}
.nav-links a:hover {
  color: #fff;
  background: rgba(255,255,255,0.08);
}
.nav-links a.active {
  color: #fff;
  background: #5a4fcf;
  font-weight: 500;
}
.nav-user {
  color: #7dd3b0;
  font-size: 13px;
  padding: 7px 14px;
  border-radius: 7px;
  cursor: pointer;
  position: relative;
  transition: background 0.2s;
}
.nav-user:hover { background: rgba(255,255,255,0.08); }
.profile-dropdown {
  display: none;
  position: absolute;
  top: 40px;
  right: 0;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
  min-width: 220px;
  z-index: 999;
  overflow: hidden;
}
.nav-user:hover .profile-dropdown { display: block; }
.profile-header {
  background: #1a1f36;
  padding: 16px;
  text-align: center;
}
.profile-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: #5a4fcf;
  color: #fff;
  font-size: 20px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 8px;
}
.profile-name {
  color: #fff;
  font-size: 14px;
  font-weight: 500;
}
.profile-role {
  color: #b0b8d1;
  font-size: 12px;
  margin-top: 2px;
}
.profile-email {
  color: #718096;
  font-size: 12px;
  margin-top: 2px;
}
.profile-body { padding: 10px; }
.profile-item {
  display: block;
  padding: 9px 12px;
  border-radius: 7px;
  font-size: 13px;
  color: #4a5568;
  text-decoration: none;
  transition: background 0.15s;
}
.profile-item:hover { background: #f7fafc; color: #1a1f36; }
.profile-divider {
  border: none;
  border-top: 1px solid #e2e8f0;
  margin: 6px 0;
}
.btn-logout {
  background: #e53e3e;
  color: #fff !important;
  padding: 7px 16px;
  border-radius: 7px;
  font-size: 14px;
  text-decoration: none;
  transition: background 0.2s;
  margin-left: 6px;
}
.btn-logout:hover { background: #c53030 !important; }
</style>

<nav class="navbar">
  <a href="/acadportal/<?= $_SESSION['role'] ?>/dashboard.php" class="nav-brand">
    Smart Academic Portal
  </a>
  <div class="nav-links">
    <?php if(isset($_SESSION['role'])): ?>

      <?php if($_SESSION['role'] == 'student'): ?>
        <a href="/acadportal/student/dashboard.php"
           class="<?= $current=='dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/acadportal/student/my_courses.php"
           class="<?= $current=='my_courses.php' ? 'active' : '' ?>">My Courses</a>
        <a href="/acadportal/student/my_grades.php"
           class="<?= $current=='my_grades.php' ? 'active' : '' ?>">My Grades</a>
        <a href="/acadportal/student/results.php"
           class="<?= $current=='results.php' ? 'active' : '' ?>">Results</a>

      <?php elseif($_SESSION['role'] == 'teacher'): ?>
        <a href="/acadportal/teacher/dashboard.php"
           class="<?= $current=='dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/acadportal/teacher/create_quiz.php"
           class="<?= $current=='create_quiz.php' ? 'active' : '' ?>">Create Quiz</a>
        <a href="/acadportal/teacher/view_results.php"
           class="<?= $current=='view_results.php' ? 'active' : '' ?>">View Results</a>
        <a href="/acadportal/teacher/enter_grades.php"
           class="<?= $current=='enter_grades.php' ? 'active' : '' ?>">Grades</a>

      <?php elseif($_SESSION['role'] == 'admin'): ?>
        <a href="/acadportal/admin/dashboard.php"
           class="<?= $current=='dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/acadportal/admin/manage_users.php"
           class="<?= $current=='manage_users.php' ? 'active' : '' ?>">Users</a>
        <a href="/acadportal/admin/manage_courses.php"
           class="<?= $current=='manage_courses.php' ? 'active' : '' ?>">Courses</a>
        <a href="/acadportal/admin/enroll.php"
           class="<?= $current=='enroll.php' ? 'active' : '' ?>">Enroll</a>
      <?php endif; ?>

      <?php
        // Get first letter of name for avatar
        $initials = strtoupper(substr($_SESSION['user_name'], 0, 1));

        // Get user email from DB for profile
        $uid      = $_SESSION['user_id'];
        $udata    = mysqli_fetch_assoc(mysqli_query($conn,
                    "SELECT email FROM users WHERE id=$uid"));
        $email    = $udata['email'];
      ?>

      <!-- Profile dropdown -->
      <div class="nav-user">
        Hi, <?= $_SESSION['user_name'] ?> ▾
        <div class="profile-dropdown">
          <div class="profile-header">
            <div class="profile-avatar"><?= $initials ?></div>
            <div class="profile-name"><?= $_SESSION['user_name'] ?></div>
            <div class="profile-role"><?= ucfirst($_SESSION['role']) ?></div>
            <div class="profile-email"><?= $email ?></div>
          </div>
          <div class="profile-body">
            <?php if($_SESSION['role'] == 'student'): ?>
              <a href="/acadportal/student/my_grades.php" class="profile-item">My Grades</a>
              <a href="/acadportal/student/results.php"   class="profile-item">My Results</a>
            <?php elseif($_SESSION['role'] == 'teacher'): ?>
              <a href="/acadportal/teacher/create_quiz.php"  class="profile-item">Create Quiz</a>
              <a href="/acadportal/teacher/view_results.php" class="profile-item">View Results</a>
            <?php elseif($_SESSION['role'] == 'admin'): ?>
              <a href="/acadportal/admin/manage_users.php"   class="profile-item">Manage Users</a>
              <a href="/acadportal/admin/manage_courses.php" class="profile-item">Manage Courses</a>
            <?php endif; ?>
            <hr class="profile-divider">
            <a href="/acadportal/auth/logout.php" class="profile-item"
               style="color:#e53e3e">Logout</a>
          </div>
        </div>
      </div>

      <a href="/acadportal/auth/logout.php" class="btn-logout">Logout</a>

    <?php endif; ?>
  </div>
</nav>