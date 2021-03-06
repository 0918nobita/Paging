<?php
error_reporting(E_ALL & ~E_NOTICE);
define('COMMENTS_PER_PAGE', 5);

if (ctype_digit($_GET["page"])) {
	$page = (int) $_GET["page"];
} else {
	$page = 1;
}

try {
	$dbh = new PDO('sqlite:database.sqlite3');

	$total = $dbh->query('SELECT count(*) FROM comments')->fetchColumn();
	$totalPages = ceil($total / COMMENTS_PER_PAGE);
	if ($page > $totalPages) $page = 1;
	$offset = COMMENTS_PER_PAGE * ($page - 1);

	$sql = 'SELECT * FROM comments LIMIT :offset , :count';
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
	$stmt->bindValue(':count', COMMENTS_PER_PAGE, PDO::PARAM_INT);
	$stmt->execute();
	$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
	echo $e->getMessage();
	die();
}
$from = $offset + 1;
$to = ($offset + COMMENTS_PER_PAGE) < $total ? ($offset + COMMENTS_PER_PAGE) : $total;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>コメント一覧</title>
</head>
<body>
<h1>コメント一覧</h1>
<p>全<?= $total ?>件中、<?= $from ?>件～<?= $to ?>件を表示しています</p>
<ul>
	<?php foreach ($comments as $comment) : ?>
		<li><?php echo htmlspecialchars($comment['comment'],ENT_QUOTES, 'UTF-8'); ?></li>
	<?php endforeach; ?>
</ul>
<?php if ($page > 1) : ?>
	<a href="?page=<?= $page - 1 ?>">前</a>
<?php endif; ?>
<?php for ($i = 1; $i <= $totalPages; $i++) : ?>
	<?php if ($page == $i) : ?>
		<strong><?= $i ?></strong>
	<?php else : ?>
		<a href="?page=<?= $i ?>"><?= $i ?></a>
	<?php endif; ?>
<?php endfor; ?>
<?php if ($page < $totalPages) : ?>
	<a href="?page=<?= $page + 1 ?>">次</a>
<?php endif; ?>
</body>
</html>
