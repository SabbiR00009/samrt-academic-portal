<?php
// teacher/create_quiz.php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
if ($_SESSION['role'] != 'teacher') { header("Location: ../auth/login.php"); exit; }

$teacher_id = $_SESSION['user_id'];
$msg = '';

// Step 1: Create quiz
if (isset($_POST['create_quiz'])) {
    $title      = $_POST['title'];
    $course_id  = $_POST['course_id'];
    $time_limit = $_POST['time_limit'];
    $total      = $_POST['total_marks'];
    mysqli_query($conn,
        "INSERT INTO quizzes (course_id, title, time_limit, total_marks)
         VALUES ($course_id, '$title', $time_limit, $total)");
    $msg = "Quiz created! Now add questions below.";
}

// Step 2: Add question to a quiz
if (isset($_POST['add_question'])) {
    $quiz_id  = $_POST['quiz_id'];
    $qtext    = $_POST['question_text'];
    $a = $_POST['option_a']; $b = $_POST['option_b'];
    $c = $_POST['option_c']; $d = $_POST['option_d'];
    $correct  = $_POST['correct_option'];
    $marks    = $_POST['marks'];
    mysqli_query($conn,
        "INSERT INTO questions
         (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
         VALUES ($quiz_id, '$qtext', '$a', '$b', '$c', '$d', '$correct', $marks)");
    $msg = "Question added!";
}

// Get this teacher's courses
$courses = mysqli_query($conn,
    "SELECT * FROM courses WHERE teacher_id=$teacher_id");

// Get all quizzes for this teacher's courses
$quizzes = mysqli_query($conn,
    "SELECT q.*, c.title AS course_name
     FROM quizzes q JOIN courses c ON q.course_id=c.id
     WHERE c.teacher_id=$teacher_id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create Quiz</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Create a Quiz</h2>
  <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

  <div class="card">
    <h3>New Quiz</h3>
    <form method="POST">
      <label>Quiz Title</label>
      <input type="text" name="title" placeholder="e.g. Chapter 3 Quiz" required>

      <label>Course</label>
      <select name="course_id" required>
        <?php
          mysqli_data_seek($courses, 0);
          while($c = mysqli_fetch_assoc($courses)):
        ?>
          <option value="<?= $c['id'] ?>"><?= $c['title'] ?> (<?= $c['code'] ?>)</option>
        <?php endwhile; ?>
      </select>

      <label>Time Limit (minutes)</label>
      <input type="number" name="time_limit" value="30" min="5" max="180">

      <label>Total Marks</label>
      <input type="number" name="total_marks" value="10" min="1">

      <button type="submit" name="create_quiz">Create Quiz</button>
    </form>
  </div>

  <div class="card">
    <h3>Add Question to Existing Quiz</h3>
    <form method="POST">
      <label>Select Quiz</label>
      <select name="quiz_id" required>
        <?php while($q = mysqli_fetch_assoc($quizzes)): ?>
          <option value="<?= $q['id'] ?>"><?= $q['title'] ?> — <?= $q['course_name'] ?></option>
        <?php endwhile; ?>
      </select>

      <label>Question Text</label>
      <textarea name="question_text" rows="3" required></textarea>

      <label>Option A</label><input type="text" name="option_a" required>
      <label>Option B</label><input type="text" name="option_b" required>
      <label>Option C</label><input type="text" name="option_c" required>
      <label>Option D</label><input type="text" name="option_d" required>

      <label>Correct Option</label>
      <select name="correct_option">
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
      </select>

      <label>Marks for this question</label>
      <input type="number" name="marks" value="1" min="1">

      <button type="submit" name="add_question">Add Question</button>
    </form>
  </div>
</div>
</body>
</html>