<?php
define('PUBLIC_PAGE', true);
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();
    $username = trim($_POST['username'] ?? '');
    $name     = trim($_POST['name'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    if ($username==='' || $name==='' || $phone==='' || $email==='' || $password==='' ) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, password_hash, name, phone, email) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss', $username, $hash, $name, $phone, $email);
        if ($stmt->execute()) { header('Location: login.php?msg=Account+created.+Please+log+in.'); exit; }
        else { $error = 'Username or email already exists.'; }
    }
}
page_header('Register');
?>
<div class="row">
  <div class="col-lg-7">
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="post" action="" class="mt-2">
      <?php csrf_field(); ?>
      <div class="mb-3"><label class="form-label">Username</label><input class="form-control" type="text" name="username"></div>
      <div class="mb-3"><label class="form-label">Full Name</label><input class="form-control" type="text" name="name"></div>
      <div class="mb-3"><label class="form-label">Phone</label><input class="form-control" type="text" name="phone"></div>
      <div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email"></div>
      <div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password"></div>
      <div class="mb-3"><label class="form-label">Confirm Password</label><input class="form-control" type="password" name="confirm"></div>
      <button class="btn btn-primary" type="submit">Register</button>
      <a class="btn btn-link" href="login.php">Back to login</a>
    </form>
  </div>
</div>
<?php page_footer(); ?>
