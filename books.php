<?php
require_once 'config/config.php'; require_once 'includes/functions.php'; require_login(); verify_csrf();
if($_SERVER['REQUEST_METHOD']==='POST'){
 $action=$_POST['action']??'';
 if($action==='save'){
  $id=(int)($_POST['id']??0); $title=trim($_POST['title']??''); $author=trim($_POST['author']??''); $total=max(0,(int)($_POST['total_copies']??1)); $cat=(int)($_POST['category_id']??0) ?: null;
  if($title===''||$author===''||$total<1){flash('Title, author and at least one copy are required.','error');redirect('books.php');}
  if($id){
   $old=$pdo->prepare('SELECT total_copies,available_copies FROM books WHERE id=?');$old->execute([$id]);$o=$old->fetch();
   if(!$o){flash('Book not found.','error');redirect('books.php');}
   $issued=$o['total_copies']-$o['available_copies']; if($total<$issued){flash('Total copies cannot be less than copies currently issued.','error');redirect('books.php');}
   $avail=$total-$issued;
   $q=$pdo->prepare('UPDATE books SET isbn=?,title=?,author=?,publisher=?,category_id=?,published_year=?,shelf_no=?,total_copies=?,available_copies=? WHERE id=?');
   $q->execute([trim($_POST['isbn']??'')?:null,$title,$author,trim($_POST['publisher']??''),$cat,$_POST['published_year']!==''?(int)$_POST['published_year']:null,trim($_POST['shelf_no']??''),$total,$avail,$id]);
   flash('Book details updated.');
  } else {
   $q=$pdo->prepare('INSERT INTO books(isbn,title,author,publisher,category_id,published_year,shelf_no,total_copies,available_copies) VALUES(?,?,?,?,?,?,?,?,?)');
   try{$q->execute([trim($_POST['isbn']??'')?:null,$title,$author,trim($_POST['publisher']??''),$cat,$_POST['published_year']!==''?(int)$_POST['published_year']:null,trim($_POST['shelf_no']??''),$total,$total]);flash('Book added successfully.');}
   catch(PDOException $e){flash('Could not add book. Check whether the ISBN already exists.','error');}
  } redirect('books.php');
 }
 if($action==='delete'){ $id=(int)$_POST['id']; $s=$pdo->prepare("SELECT COUNT(*) FROM loans WHERE book_id=? AND status='Issued'");$s->execute([$id]);
  if($s->fetchColumn()){flash('This book has active loans and cannot be deleted.','error');}
  else{try{$pdo->prepare('DELETE FROM books WHERE id=?')->execute([$id]);flash('Book deleted.');}catch(PDOException $e){flash('Book has transaction history and cannot be deleted.','error');}} redirect('books.php');}
}
$edit=null;if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM books WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
$q=trim($_GET['q']??'');$sql="SELECT b.*,c.name category FROM books b LEFT JOIN categories c ON c.id=b.category_id";$args=[];
if($q!==''){$sql.=" WHERE b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?";$args=["%$q%","%$q%","%$q%"];}
$sql.=" ORDER BY b.title";$s=$pdo->prepare($sql);$s->execute($args);$books=$s->fetchAll();$cats=$pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$page_title='Book Catalogue';require 'includes/header.php'; ?>
<div class="page-heading"><div><p class="eyebrow">COLLECTION</p><h1>Book catalogue</h1><p class="muted">Add, search and maintain your library collection.</p></div><a class="btn primary" href="#book-form">＋ Add new book</a></div>
<div class="content-grid"><section class="panel form-panel" id="book-form"><h2><?=$edit?'Edit book':'Add a book'?></h2><form method="post"><?=csrf_field()?><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=e($edit['id']??'')?>"><div class="form-grid">
<div class="field full"><label>Book title *</label><input name="title" required value="<?=e($edit['title']??'')?>"></div>
<div class="field"><label>Author *</label><input name="author" required value="<?=e($edit['author']??'')?>"></div><div class="field"><label>ISBN</label><input name="isbn" value="<?=e($edit['isbn']??'')?>"></div>
<div class="field"><label>Publisher</label><input name="publisher" value="<?=e($edit['publisher']??'')?>"></div><div class="field"><label>Category</label><select name="category_id"><option value="">Select category</option><?php foreach($cats as $c):?><option value="<?=$c['id']?>" <?=($edit['category_id']??'')==$c['id']?'selected':''?>><?=e($c['name'])?></option><?php endforeach;?></select></div>
<div class="field"><label>Published year</label><input type="number" name="published_year" min="1000" max="2100" value="<?=e($edit['published_year']??'')?>"></div><div class="field"><label>Shelf number</label><input name="shelf_no" value="<?=e($edit['shelf_no']??'')?>"></div>
<div class="field"><label>Total copies *</label><input type="number" min="1" name="total_copies" required value="<?=e($edit['total_copies']??1)?>"></div></div><button class="btn primary"><?=$edit?'Update book':'Save book'?></button><?php if($edit):?> <a class="btn light" href="books.php">Cancel</a><?php endif;?></form></section>
<section class="panel"><div class="panel-heading"><div><h2>All books <span class="count"><?=$books?count($books):0?></span></h2></div><form class="search-form" method="get"><input name="q" value="<?=e($q)?>" placeholder="Search title, author, ISBN"><button class="btn dark">Search</button></form></div>
<div class="table-wrap"><table><thead><tr><th>Book / ISBN</th><th>Author</th><th>Category</th><th>Copies</th><th>Shelf</th><th>Actions</th></tr></thead><tbody><?php foreach($books as $b):?><tr><td><b><?=e($b['title'])?></b><small><?=e($b['isbn']??'No ISBN')?></small></td><td><?=e($b['author'])?></td><td><?=e($b['category']??'—')?></td><td><b><?=$b['available_copies']?> / <?=$b['total_copies']?></b><small>available / total</small></td><td><?=e($b['shelf_no']??'—')?></td><td class="actions"><a href="books.php?edit=<?=$b['id']?>">Edit</a><form method="post" onsubmit="return confirm('Delete this book?')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$b['id']?>"><button class="link danger-link">Delete</button></form></td></tr><?php endforeach;?><?php if(!$books):?><tr><td colspan="6" class="empty">No books found.</td></tr><?php endif;?></tbody></table></div></section></div>
<?php require 'includes/footer.php'; ?>
