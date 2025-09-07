<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/layout.php';
$user_id = (int)$_SESSION['user_id'];
$noti = $mysqli->prepare("SELECT id, message, created_at, `read` FROM notifications WHERE user_id=? ORDER BY created_at DESC");
$noti->bind_param('i', $user_id); $noti->execute();
$res = $noti->get_result();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf_or_die();
    $nid = (int)($_POST['id'] ?? 0);
    if ($nid > 0) {
        $stmt = $mysqli->prepare("UPDATE notifications SET `read`=1 WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $nid, $user_id); $stmt->execute();
        header('Location: notifications.php'); exit;
    }
}
page_header('Notifications');
?>
<ul class="list-group">
  <?php while($n = $res->fetch_assoc()): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <span><?php echo htmlspecialchars($n['message']).' — '.htmlspecialchars($n['created_at']); ?></span>
      <?php if (!$n['read']): ?>
        <form method="post" class="m-0">
          <?php csrf_field(); ?>
          <input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>">
          <button class="btn btn-sm btn-outline-primary" type="submit">Mark read</button>
        </form>
      <?php else: ?>
        <span class="badge bg-success">Read</span>
      <?php endif; ?>
    </li>
  <?php endwhile; ?>
</ul>
<p class="mt-3"><a class="btn btn-outline-secondary" href="dashboard.php">Back</a></p>
<?php page_footer(); ?>
