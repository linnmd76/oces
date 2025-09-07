<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';
page_header('Dashboard');
?>
<div class="row g-3">
  <div class="col-md-4">
    <a class="card text-decoration-none" href="list_courses.php">
      <div class="card-body">
        <h5 class="card-title">Browse Courses</h5>
        <p class="card-text">Find offerings by semester and enroll or waitlist.</p>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a class="card text-decoration-none" href="my_courses.php">
      <div class="card-body">
        <h5 class="card-title">My Courses</h5>
        <p class="card-text">View enrollments and manage cancellations.</p>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a class="card text-decoration-none" href="notifications.php">
      <div class="card-body">
        <h5 class="card-title">Notifications</h5>
        <p class="card-text">See updates about enrollments and waitlists.</p>
      </div>
    </a>
  </div>
</div>
<?php page_footer(); ?>
