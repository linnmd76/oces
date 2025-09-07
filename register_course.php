<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';
verify_csrf_or_die();
$user_id  = (int)$_SESSION['user_id'];
$course_id = (int)($_POST['course_id'] ?? 0);
if ($course_id <= 0) { header('Location: list_courses.php'); exit; }
$stmt = $mysqli->prepare("SELECT capacity FROM courses WHERE id=?");
$stmt->bind_param('i', $course_id); $stmt->execute();
$capRow = $stmt->get_result()->fetch_assoc();
if (!$capRow) { header('Location: list_courses.php'); exit; }
$capacity = (int)$capRow['capacity'];
$stmt = $mysqli->prepare("SELECT COUNT(*) AS c FROM enrollment WHERE course_id=? AND status='enrolled'");
$stmt->bind_param('i', $course_id); $stmt->execute();
$enrolled = (int)$stmt->get_result()->fetch_assoc()['c'];
$stmt = $mysqli->prepare("SELECT 1 FROM enrollment WHERE user_id=? AND course_id=? AND status='enrolled'");
$stmt->bind_param('ii', $user_id, $course_id); $stmt->execute();
if ($stmt->get_result()->fetch_row()) { header('Location: my_courses.php?msg=Already+enrolled'); exit; }
$stmt = $mysqli->prepare("SELECT 1 FROM waitlist WHERE user_id=? AND course_id=?");
$stmt->bind_param('ii', $user_id, $course_id); $stmt->execute();
if ($stmt->get_result()->fetch_row()) { header('Location: my_courses.php?msg=Already+on+waitlist'); exit; }
if ($enrolled < $capacity) {
    $stmt = $mysqli->prepare("INSERT INTO enrollment (user_id, course_id, status) VALUES (?,?, 'enrolled')");
    $stmt->bind_param('ii', $user_id, $course_id); $stmt->execute();
    $msg = 'You have been enrolled.';
    $stmt = $mysqli->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param('is', $user_id, $msg); $stmt->execute();
    header('Location: my_courses.php?msg=Enrolled'); exit;
} else {
    $stmt = $mysqli->prepare("SELECT COALESCE(MAX(position),0)+1 AS nextpos FROM waitlist WHERE course_id=?");
    $stmt->bind_param('i', $course_id); $stmt->execute();
    $nextpos = (int)$stmt->get_result()->fetch_assoc()['nextpos'];
    $stmt = $mysqli->prepare("INSERT INTO waitlist (user_id, course_id, position) VALUES (?,?,?)");
    $stmt->bind_param('iii', $user_id, $course_id, $nextpos); $stmt->execute();
    $msg = 'Course is full. You have been added to the waitlist.';
    $stmt = $mysqli->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param('is', $user_id, $msg); $stmt->execute();
    header('Location: my_courses.php?msg=Waitlisted'); exit;
}
