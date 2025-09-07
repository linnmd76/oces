<?php
define('PUBLIC_PAGE', true);
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/layout.php';
page_header('OCES - Home');
?>
<div class="row">
  <div class="col-lg-8">
    <h2>Online Course Enrollment System</h2>
    <?php if (!empty($_GET['msg'])): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <p class="lead">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Student'); ?>.</p>
      <a class="btn btn-primary me-2" href="dashboard.php">Dashboard</a>
      <a class="btn btn-outline-primary me-2" href="list_courses.php">Browse Courses</a>
      <a class="btn btn-outline-secondary" href="my_courses.php">My Courses</a>
    <?php else: ?>
      <p class="lead">Please log in or create an account to enroll in courses.</p>
      <a class="btn btn-primary me-2" href="login.php">Login</a>
      <a class="btn btn-outline-primary" href="register.php">Register</a>
    <?php endif; ?>
  </div>
</div>
<?php page_footer(); ?>
