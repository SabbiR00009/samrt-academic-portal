<?php // includes/header.php
$current = basename($_SERVER['PHP_SELF']);
$folder  = basename(dirname($_SERVER['PHP_SELF']));
?>
<nav class="navbar">
  <div class="nav-brand">Smart Academic Portal</div>
  <div class="nav-links">
    <?php if(isset($_SESSION['role'])): ?>

      <?php if($_SESSION['role'] == 'student'): ?>
        <a href="/acadportal/student/dashboard.php"
           class="<?= $current=='dashboard.php' ? 'active' : '' ?>">
           Dashboard
        </a>
        <a href="/acadportal/student/my_courses.php"
           class="<?= $current=='my_courses.php' ? 'active' : '' ?>">
           My Courses
        </a>
        <a href="/acadportal/student/my_grades.php"
           class="<?= $current=='my_grades.php' ? 'active' : '' ?>">
           My Grades
        </a>
        <a href="/acadportal/student/results.php"
           class="<?= $current=='results.php' ? 'active' : '' ?>">
           Results
        </a>

      <?php elseif($_SESSION['role'] == 'teacher'): ?>
        <a href="/acadportal/teacher/dashboard.php"
           class="<?= $current=='dashboard.php' ? 'active' : '' ?>">
           Dashboard
        </a>
        <a href="/acadportal/teacher/create_quiz.php"
           class="<?= $current=='create_quiz.php' ? 'active' : '' ?>">
           Create Quiz
        </a>
        <a href="/acadportal/teacher/view_results.php"
           class="<?= $current=='view_results.php' ? 'active' : '' ?>">
           View Results
        </a>
        <a href="/acadportal/teacher/enter_grades.php"
           class="<?= $current=='enter_grades.php' ? 'active' : '' ?>">
           Grades
        </a>

      <?php elseif($_SESSION['role'] == 'admin'): ?>
        <a href="/acadportal/admin/dashboard.php"
           class="<?= $current=='dashboard.php' ? 'active' : '' ?>">
           Dashboard
        </a>
        <a href="/acadportal/admin/manage_users.php"
           class="<?= $current=='manage_users.php' ? 'active' : '' ?>">
           Users
        </a>
        <a href="/acadportal/admin/manage_courses.php"
           class="<?= $current=='manage_courses.php' ? 'active' : '' ?>">
           Courses
        </a>
        <a href="/acadportal/admin/enroll.php"
           class="<?= $current=='enroll.php' ? 'active' : '' ?>">
           Enroll
        </a>
      <?php endif; ?>

      <span class="nav-user">Hi, <?= $_SESSION['user_name'] ?></span>
      <a href="/acadportal/auth/logout.php" class="btn-logout">Logout</a>

    <?php endif; ?>
  </div>
</nav>