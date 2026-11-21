<?php require_once __DIR__.'/functions.php'; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($page_title ?? 'Library Management System') ?></title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="topbar"><a class="brand" href="index.php"><span class="brand-icon">▤</span> Library<span>Desk</span></a>
<?php if(!empty($_SESSION['admin_id'])): ?><nav><a href="index.php">Dashboard</a><a href="books.php">Books</a><a href="members.php">Members</a><a href="issue.php">Issue / Return</a><a href="reports.php">Reports</a><a href="change-password.php">Password</a><a class="logout" href="logout.php">Logout</a></nav><?php endif; ?></header>
<main class="container"><?php show_flash(); ?>
