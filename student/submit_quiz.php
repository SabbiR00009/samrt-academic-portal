<?php
session_start();
include '../config/db.php';

$quiz_id    = $_POST['quiz_id'];
$student_id = $_SESSION['user_id'];
$answers    = $_POST['ans'];
$score      = 0;

foreach ($answers as $q_id => $chosen) {
    $q = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT correct_option, marks FROM questions WHERE id=$q_id"));
    if ($q['correct_option'] == $chosen) $score += $q['marks'];
}

mysqli_query($conn, "INSERT INTO quiz_attempts (quiz_id, student_id, score)
                     VALUES ($quiz_id, $student_id, $score)");

// Auto-update grade table
$course_id = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT course_id FROM quizzes WHERE id=$quiz_id"))['course_id'];
$avg = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT AVG(score) as avg FROM quiz_attempts
     WHERE student_id=$student_id AND quiz_id IN
     (SELECT id FROM quizzes WHERE course_id=$course_id)"))['avg'];

mysqli_query($conn, "INSERT INTO grades (student_id, course_id, quiz_avg)
                     VALUES ($student_id, $course_id, $avg)
                     ON DUPLICATE KEY UPDATE quiz_avg=$avg, gpa=($avg/10)");

header("Location: results.php?score=$score"); exit;
?>