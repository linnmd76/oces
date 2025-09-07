<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$IDLE_LIMIT = 20 * 60;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $IDLE_LIMIT)) {
    session_unset(); session_destroy();
    header('Location: login.php?msg=Session+expired.+Please+log+in+again.'); exit;
}
$_SESSION['last_activity'] = time();
if (!defined('PUBLIC_PAGE')) {
    if (empty($_SESSION['user_id'])) { header('Location: login.php?msg=Please+log+in'); exit; }
}
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }
    return $_SESSION['csrf_token'];
}
function csrf_field() {
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    echo '<input type="hidden" name="csrf_token" value="' . $t . '">';
}
function verify_csrf_or_die() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sent  = $_POST['csrf_token'] ?? '';
        $sess  = $_SESSION['csrf_token'] ?? '';
        if (!$sent || !$sess || !hash_equals($sess, $sent)) { http_response_code(400); die('Bad request: CSRF token invalid.'); }
    }
}
?>
