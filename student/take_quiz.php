<?php
session_start();
include '../config/db.php';
$quiz_id   = $_GET['id'];
$quiz      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM quizzes WHERE id=$quiz_id"));
$questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id=$quiz_id");
?>
<!DOCTYPE html><html><body>
<h2><?= $quiz['title'] ?></h2>
<p>Time left: <strong id="timer"></strong></p>

<form method="POST" action="submit_quiz.php" id="quizForm">
  <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
  <?php while($q = mysqli_fetch_assoc($questions)): ?>
    <p><?= $q['question_text'] ?></p>
    <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="A"> <?= $q['option_a'] ?></label><br>
    <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="B"> <?= $q['option_b'] ?></label><br>
    <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="C"> <?= $q['option_c'] ?></label><br>
    <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="D"> <?= $q['option_d'] ?></label><br><br>
  <?php endwhile; ?>
  <button type="submit">Submit Quiz</button>
</form>

<script>
  let secs = <?= $quiz['time_limit'] * 60 ?>;
  const el = document.getElementById('timer');
  const t  = setInterval(() => {
    let m = Math.floor(secs/60), s = secs % 60;
    el.textContent = m + ':' + (s < 10 ? '0' : '') + s;
    if (--secs < 0) { clearInterval(t); document.getElementById('quizForm').submit(); }
  }, 1000);
</script>
</body></html>