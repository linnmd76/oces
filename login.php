<?php
define('PUBLIC_PAGE', true);
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();
    $username = trim($_POST['username'] ?? ''); $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') { $error = 'Please enter username and password.'; }
    else {
        $stmt = $mysqli->prepare("SELECT id, username, password_hash FROM users WHERE username=?");
        $stmt->bind_param('s', $username); $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if (password_verify($password, $row['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$row['id']; $_SESSION['username'] = $row['username']; $_SESSION['last_activity'] = time();
                header('Location: dashboard.php'); exit;
            }
        }
        $error = 'Invalid credentials.';
    }
}
page_header('Login');
?>
<div class="row">
  <div class="col-lg-6">
    <?php if (!empty($_GET['msg'])): ?><div class="alert alert-info"><?php echo htmlspecialchars($_GET['msg']); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="post" action="" class="mt-2">
      <?php csrf_field(); ?>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input class="form-control" type="text" name="username">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password">
      </div>
      <button class="btn btn-primary" type="submit">Login</button>
      <a class="btn btn-link" href="register.php">Create an account</a>
    </form>
  </div>
</div>
<?php page_footer(); ?>
