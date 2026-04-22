<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SESSION['role'] != 'student') { header("Location: /acadportal/auth/login.php"); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: /acadportal/student/my_courses.php"); exit; }

$student_id = $_SESSION['user_id'];
$quiz_id    = intval($_POST['quiz_id']);
$answers    = isset($_POST['ans']) ? $_POST['ans'] : [];

// Verify the student is enrolled in the course this quiz belongs to
$access = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT q.id, q.course_id FROM quizzes q
     JOIN courses c ON q.course_id = c.id
     JOIN enrollments e ON e.course_id = c.id
     WHERE q.id = $quiz_id AND e.student_id = $student_id"));

if (!$access) {
    header("Location: /acadportal/student/my_courses.php");
    exit;
}

$course_id = intval($access['course_id']);
$score     = 0;

foreach ($answers as $q_id => $chosen) {
    $q_id = intval($q_id);
    $q    = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT correct_option, marks FROM questions WHERE id=$q_id AND quiz_id=$quiz_id"));
    if ($q && $q['correct_option'] === $chosen) {
        $score += floatval($q['marks']);
    }
}

// Record the attempt
mysqli_query($conn,
    "INSERT INTO quiz_attempts (quiz_id, student_id, score)
     VALUES ($quiz_id, $student_id, $score)");

// Auto-update quiz average in grades table
$avg_row = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT AVG(qa.score) as avg
     FROM quiz_attempts qa
     JOIN quizzes q ON qa.quiz_id = q.id
     WHERE qa.student_id = $student_id
     AND q.course_id = $course_id"));
$quiz_avg = floatval($avg_row['avg'] ?? 0);

mysqli_query($conn,
    "INSERT INTO grades (student_id, course_id, quiz_avg)
     VALUES ($student_id, $course_id, $quiz_avg)
     ON DUPLICATE KEY UPDATE quiz_avg = $quiz_avg");

header("Location: /acadportal/student/results.php?score=$score");
exit;
?>