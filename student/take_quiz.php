<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'student') { header("Location: /acadportal/auth/login.php"); exit; }

$quiz_id = intval($_GET['id']);
$sid     = $_SESSION['user_id'];

// Verify student is enrolled in the course this quiz belongs to
$access = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT q.id FROM quizzes q
     JOIN courses c ON q.course_id = c.id
     JOIN enrollments e ON e.course_id = c.id
     WHERE q.id = $quiz_id AND e.student_id = $sid"));
if (!$access) {
    header("Location: /acadportal/student/my_courses.php");
    exit;
}

$quiz      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM quizzes WHERE id=$quiz_id"));
$questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id=$quiz_id ORDER BY id");

if (!$quiz) {
    header("Location: /acadportal/student/my_courses.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($quiz['title']) ?> — Smart Academic Portal</title>
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .quiz-header {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 20px 24px;
      margin-bottom: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }
    .quiz-header h2 { margin: 0; font-size: 20px; color: #1a1f36; }
    .timer-box {
      background: #ebf8ff;
      border: 1px solid #bee3f8;
      border-radius: 8px;
      padding: 8px 20px;
      text-align: center;
    }
    .timer-box .timer-label { font-size: 11px; color: #2b6cb0; font-weight: 500; text-transform: uppercase; letter-spacing: .05em; }
    .timer-box #timer { font-size: 26px; font-weight: 700; color: #2b6cb0; display: block; line-height: 1.2; }
    #timer.warning { color: #dd6b20; }
    #timer.danger  { color: #e53e3e; }

    .question-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 24px;
      margin-bottom: 18px;
    }
    .question-num {
      font-size: 11px;
      font-weight: 600;
      color: #2b6cb0;
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: 8px;
    }
    .question-text {
      font-size: 16px;
      font-weight: 600;
      color: #1a1f36;
      margin-bottom: 18px;
      line-height: 1.5;
    }
    .options { display: flex; flex-direction: column; gap: 10px; }
    .option-label {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 16px;
      border: 1.5px solid #e2e8f0;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      color: #2d3748;
      transition: all 0.15s;
      user-select: none;
    }
    .option-label:hover {
      border-color: #4299e1;
      background: #ebf8ff;
    }
    .option-label input[type="radio"] {
      width: 16px;
      height: 16px;
      flex-shrink: 0;
      accent-color: #2b6cb0;
      cursor: pointer;
    }
    .option-label:has(input:checked) {
      border-color: #2b6cb0;
      background: #ebf8ff;
      color: #1a1f36;
    }
    .marks-badge {
      float: right;
      background: #edf2f7;
      color: #4a5568;
      font-size: 11px;
      font-weight: 500;
      padding: 2px 8px;
      border-radius: 99px;
    }
    .submit-bar {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 20px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 8px;
    }
    .submit-bar .info { font-size: 13px; color: #718096; }
    .submit-bar button {
      margin: 0;
      padding: 11px 32px;
      font-size: 15px;
    }
  </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">

  <!-- Quiz header with timer -->
  <div class="quiz-header">
    <div>
      <div style="font-size:12px;color:#718096;margin-bottom:4px">Quiz</div>
      <h2><?= htmlspecialchars($quiz['title']) ?></h2>
    </div>
    <div class="timer-box">
      <span class="timer-label">Time left</span>
      <span id="timer">--:--</span>
    </div>
  </div>

  <!-- Quiz form -->
  <form method="POST"
        action="/acadportal/student/submit_quiz.php"
        id="quizForm"
        data-time-seconds="<?= intval($quiz['time_limit']) * 60 ?>">

    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

    <?php
    $q_num = 0;
    while ($q = mysqli_fetch_assoc($questions)):
      $q_num++;
    ?>
    <div class="question-card">
      <div class="question-num">Question <?= $q_num ?> <span class="marks-badge"><?= $q['marks'] ?> mark<?= $q['marks'] != 1 ? 's' : '' ?></span></div>
      <div class="question-text"><?= htmlspecialchars($q['question_text']) ?></div>

      <div class="options">
        <?php
        $opts = [
          'A' => $q['option_a'],
          'B' => $q['option_b'],
          'C' => $q['option_c'],
          'D' => $q['option_d'],
        ];
        foreach ($opts as $letter => $text):
          if (empty(trim($text))) continue;
        ?>
        <label class="option-label">
          <input type="radio"
                 name="ans[<?= $q['id'] ?>]"
                 value="<?= $letter ?>">
          <span style="min-width:22px;font-weight:600;color:#2b6cb0"><?= $letter ?>.</span>
          <span><?= htmlspecialchars($text) ?></span>
        </label>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endwhile; ?>

    <!-- Submit bar -->
    <div class="submit-bar">
      <span class="info">
        <?= $q_num ?> question<?= $q_num != 1 ? 's' : '' ?> &nbsp;·&nbsp;
        <?= $quiz['total_marks'] ?> total marks &nbsp;·&nbsp;
        <?= $quiz['time_limit'] ?> min time limit
      </span>
      <button type="submit"
              onclick="return confirm('Submit quiz now? You cannot change your answers after submitting.')">
        Submit Quiz
      </button>
    </div>

  </form>
</div>

<script src="../assets/quiz_timer.js"></script>
</body>
</html>