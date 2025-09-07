<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();
    $course_id = (int)($_POST['course_id'] ?? 0);
    if ($course_id > 0) {

        // Fetch course info for better notification messages
        $stmt = $mysqli->prepare("SELECT code, title FROM courses WHERE id=?");
        $stmt->bind_param('i', $course_id);
        $stmt->execute();
        $course = $stmt->get_result()->fetch_assoc();
        $course_code  = $course['code']  ?? '';
        $course_title = $course['title'] ?? '';

        // 1) Drop enrollment for current user
        $stmt = $mysqli->prepare("DELETE FROM enrollment WHERE user_id=? AND course_id=? AND status='enrolled'");
        $stmt->bind_param('ii', $user_id, $course_id);
        $stmt->execute();

        // 2) Notify the cancelling user (always)
        $selfMsg = $course_code || $course_title
            ? "You cancelled your enrollment: {$course_code} - {$course_title}."
            : "You cancelled your enrollment.";
        $stmt = $mysqli->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->bind_param('is', $user_id, $selfMsg);
        $stmt->execute();

        // 3) Promote first waitlisted (if any), notify them
        $stmt = $mysqli->prepare("SELECT id, user_id FROM waitlist WHERE course_id=? ORDER BY position ASC LIMIT 1");
        $stmt->bind_param('i', $course_id);
        $stmt->execute();
        $w = $stmt->get_result()->fetch_assoc();

        if ($w) {
            $wait_id   = (int)$w['id'];
            $next_user = (int)$w['user_id'];

            // Move to enrollment
            $stmt = $mysqli->prepare("INSERT INTO enrollment (user_id, course_id, status) VALUES (?,?, 'enrolled')");
            $stmt->bind_param('ii', $next_user, $course_id);
            $stmt->execute();

            // Remove from waitlist
            $stmt = $mysqli->prepare("DELETE FROM waitlist WHERE id=?");
            $stmt->bind_param('i', $wait_id);
            $stmt->execute();

            // Notify the promoted user
            $promoteMsg = $course_code || $course_title
                ? "A seat opened in {$course_code} - {$course_title}. You have been enrolled."
                : "A seat opened. You have been enrolled.";
            $stmt = $mysqli->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $stmt->bind_param('is', $next_user, $promoteMsg);
            $stmt->execute();
        }

        header('Location: my_courses.php?msg=Enrollment+cancelled');
        exit;
    }
}

// Build list of currently enrolled courses for this user (confirmation UI)
$enrolled = $mysqli->prepare("SELECT c.id AS course_id, c.code, c.title
                              FROM enrollment e JOIN courses c ON c.id=e.course_id
                              WHERE e.user_id=? AND e.status='enrolled' ORDER BY c.code");
$enrolled->bind_param('i', $user_id);
$enrolled->execute();
$courses = $enrolled->get_result();

page_header('Cancel Enrollment');
?>
<h2>Cancel Enrollment</h2>
<p>Select a course to drop:</p>
<ul class="list-group">
<?php while($c = $courses->fetch_assoc()): ?>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <span><?php echo htmlspecialchars($c['code'].' - '.$c['title']); ?></span>
    <form method="post" class="m-0">
      <?php csrf_field(); ?>
      <input type="hidden" name="course_id" value="<?php echo (int)$c['course_id']; ?>">
      <button class="btn btn-sm btn-danger" type="submit">Cancel</button>
    </form>
  </li>
<?php endwhile; ?>
</ul>
<p class="mt-3"><a class="btn btn-outline-secondary" href="my_courses.php">Back</a></p>
<?php page_footer(); ?>

