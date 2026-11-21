<?php
require_once 'config/config.php'; require_once 'includes/functions.php';
if(!empty($_SESSION['admin_id'])) redirect('index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){ verify_csrf(); $u=trim($_POST['username']??''); $p=$_POST['password']??'';
 $s=$pdo->prepare('SELECT * FROM admins WHERE username=?'); $s->execute([$u]); $a=$s->fetch();
 if($a && password_verify($p,$a['password_hash'])){ session_regenerate_id(true); $_SESSION['admin_id']=$a['id']; $_SESSION['admin_name']=$a['name']; redirect('index.php'); }
 $error='Invalid username or password.';
}
$page_title='Admin Login'; require 'includes/header.php'; ?>
<section class="login-wrap"><div class="login-card"><div class="login-mark">▤</div><p class="eyebrow">LIBRARY ADMINISTRATION</p><h1>Welcome back</h1><p class="muted">Sign in to manage your library.</p>
<?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?>
<form method="post"><?=csrf_field()?><label>Username</label><input name="username" required autocomplete="username" placeholder="Enter username"><label>Password</label><input type="password" name="password" required autocomplete="current-password" placeholder="Enter password"><button class="btn primary full">Sign in</button></form><p class="hint">Initial login: <b>admin</b> / <b>password</b>. Change the password after setup.</p></div></section>
<?php require 'includes/footer.php'; ?>
