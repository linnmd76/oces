<?php
function page_header($title = 'OCES') {
  $username = $_SESSION['username'] ?? null;
  echo <<<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$title}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php">OCES</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars"
      aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbars">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
HTML;
  if ($username) {
    echo '<li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="list_courses.php">Courses</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="my_courses.php">My Courses</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>';
  }
  echo '</ul><ul class="navbar-nav ms-auto">';
  if ($username) {
    $safe = htmlspecialchars($username);
    echo "<li class='nav-item'><span class='navbar-text me-3'>Hi, {$safe}</span></li>";
    echo "<li class='nav-item'><a class='btn btn-light btn-sm' href='logout.php'>Logout</a></li>";
  } else {
    echo "<li class='nav-item me-2'><a class='btn btn-light btn-sm' href='login.php'>Login</a></li>";
    echo "<li class='nav-item'><a class='btn btn-outline-light btn-sm' href='register.php'>Register</a></li>";
  }
  echo <<<HTML
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">
HTML;
}

function page_footer() {
  echo <<<HTML
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}
?>
