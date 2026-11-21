<?php
require_once 'config/config.php'; require_once 'includes/functions.php'; require_login();
$page_title='Dashboard'; $stats=[];
$stats['books']=$pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();
$stats['copies']=$pdo->query('SELECT COALESCE(SUM(total_copies),0) FROM books')->fetchColumn();
$stats['members']=$pdo->query("SELECT COUNT(*) FROM members WHERE status='Active'")->fetchColumn();
$stats['issued']=$pdo->query("SELECT COUNT(*) FROM loans WHERE status='Issued'")->fetchColumn();
$stats['overdue']=$pdo->query("SELECT COUNT(*) FROM loans WHERE status='Issued' AND due_date<CURDATE()")->fetchColumn();
$recent=$pdo->query("SELECT l.*,b.title,m.name member_name FROM loans l JOIN books b ON b.id=l.book_id JOIN members m ON m.id=l.member_id ORDER BY l.id DESC LIMIT 6")->fetchAll();
require 'includes/header.php'; ?>
<div class="page-heading"><div><p class="eyebrow">OVERVIEW</p><h1>Dashboard</h1><p class="muted">Good day, <?=e($_SESSION['admin_name'])?>. Here is your library at a glance.</p></div><a class="btn primary" href="issue.php">＋ Issue a book</a></div>
<div class="stats-grid">
<div class="stat-card"><span class="stat-label">Book titles</span><strong><?=$stats['books']?></strong><small><?=$stats['copies']?> total copies</small><span class="stat-icon">▤</span></div>
<div class="stat-card"><span class="stat-label">Active members</span><strong><?=$stats['members']?></strong><small>Registered readers</small><span class="stat-icon">♙</span></div>
<div class="stat-card"><span class="stat-label">Books issued</span><strong><?=$stats['issued']?></strong><small>Currently on loan</small><span class="stat-icon">↗</span></div>
<div class="stat-card warning-stat"><span class="stat-label">Overdue loans</span><strong><?=$stats['overdue']?></strong><small>Require attention</small><span class="stat-icon">◷</span></div></div>
<div class="quick-grid"><a href="books.php" class="quick-card"><b>＋ Add / manage books</b><span>Maintain catalogue, copies and shelves →</span></a><a href="members.php" class="quick-card"><b>♙ Manage members</b><span>Register and update library members →</span></a><a href="reports.php" class="quick-card"><b>▥ View reports</b><span>Track issues, returns and overdue books →</span></a></div>
<section class="panel"><div class="panel-heading"><div><h2>Recent circulation</h2><p class="muted">Latest issue and return activity</p></div><a href="reports.php">View all →</a></div>
<div class="table-wrap"><table><thead><tr><th>Book</th><th>Member</th><th>Issue date</th><th>Due date</th><th>Status</th></tr></thead><tbody>
<?php foreach($recent as $r): ?><tr><td><b><?=e($r['title'])?></b></td><td><?=e($r['member_name'])?></td><td><?=e($r['issue_date'])?></td><td><?=e($r['due_date'])?></td><td><span class="pill <?=$r['status']==='Returned'?'green':($r['due_date']<date('Y-m-d')?'red':'blue')?>"><?=$r['status']==='Issued' && $r['due_date']<date('Y-m-d')?'Overdue':e($r['status'])?></span></td></tr><?php endforeach; ?>
<?php if(!$recent): ?><tr><td colspan="5" class="empty">No circulation records yet. Issue your first book to get started.</td></tr><?php endif; ?></tbody></table></div></section>
<?php require 'includes/footer.php'; ?>
