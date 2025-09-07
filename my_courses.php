<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';
$user_id = (int)$_SESSION['user_id'];
$enrolled = $mysqli->prepare("SELECT c.id AS course_id, c.code, c.title, c.semester
                              FROM enrollment e JOIN courses c ON c.id=e.course_id
                              WHERE e.user_id=? AND e.status='enrolled' ORDER BY c.code");
$enrolled->bind_param('i', $user_id); $enrolled->execute();
$enrolled_res = $enrolled->get_result();
$wait = $mysqli->prepare("SELECT c.id AS course_id, c.code, c.title, c.semester, w.position
                          FROM waitlist w JOIN courses c ON c.id=w.course_id
                          WHERE w.user_id=? ORDER BY w.position");
$wait->bind_param('i', $user_id); $wait->execute();
$wait_res = $wait->get_result();
page_header('My Courses');
?>
<?php if (!empty($_GET['msg'])): ?>
  <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>
<h3>Enrolled</h3>
<ul class="list-group mb-4">
  <?php while($c = $enrolled_res->fetch_assoc()): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <span><?php echo htmlspecialchars($c['code'].' - '.$c['title'].' ('.$c['semester'].')'); ?></span>
      <form method="post" action="delete_course.php" class="m-0">
        <?php csrf_field(); ?>
        <input type="hidden" name="course_id" value="<?php echo (int)$c['course_id']; ?>">
        <button class="btn btn-sm btn-warning" type="submit">Cancel</button>
      </form>
    </li>
  <?php endwhile; ?>
</ul>
<h3>Waitlist</h3>
<ul class="list-group">
  <?php while($w = $wait_res->fetch_assoc()): ?>
    <li class="list-group-item"><?php echo htmlspecialchars($w['position'].'. '.$w['code'].' - '.$w['title'].' ('.$w['semester'].')'); ?></li>
  <?php endwhile; ?>
</ul>
<p class="mt-3"><a class="btn btn-outline-secondary" href="list_courses.php">Browse Courses</a></p>
<?php page_footer(); ?>
