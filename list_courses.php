<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';

$semester = $_GET['semester'] ?? '';
if ($semester) {
    $stmt = $mysqli->prepare("SELECT id AS course_id, code, title, semester, capacity FROM courses WHERE semester=? ORDER BY code");
    $stmt->bind_param('s', $semester); $stmt->execute();
    $courses = $stmt->get_result();
} else {
    $courses = $mysqli->query("SELECT id AS course_id, code, title, semester, capacity FROM courses ORDER BY code");
}
page_header('Courses');
?>
<div class="d-flex align-items-end gap-3 mb-3">
  <form method="get" class="d-flex gap-2 flex-wrap">
    <div>
      <label class="form-label">Semester</label>
      <select class="form-select" name="semester">
        <option value="">All</option>
        <option value="spring" <?php if($semester==='spring') echo 'selected';?>>Spring</option>
        <option value="summer" <?php if($semester==='summer') echo 'selected';?>>Summer</option>
        <option value="fall"   <?php if($semester==='fall')   echo 'selected';?>>Fall</option>
      </select>
    </div>
    <button class="btn btn-primary align-self-end" type="submit">Filter</button>
  </form>
  <a class="btn btn-outline-secondary align-self-end" href="dashboard.php">Back</a>
</div>
<table class="table table-striped">
  <thead><tr><th>Code</th><th>Title</th><th>Semester</th><th>Capacity</th><th>Action</th></tr></thead>
  <tbody>
  <?php while($c = $courses->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($c['code']); ?></td>
      <td class="min-w-280"><?php echo htmlspecialchars($c['title']); ?></td>
      <td><?php echo htmlspecialchars($c['semester']); ?></td>
      <td><?php echo (int)$c['capacity']; ?></td>
      <td>
        <form method="post" action="register_course.php" class="d-inline">
          <?php csrf_field(); ?>
          <input type="hidden" name="course_id" value="<?php echo (int)$c['course_id']; ?>">
          <button class="btn btn-sm btn-success" type="submit">Enroll / Waitlist</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<?php page_footer(); ?>
