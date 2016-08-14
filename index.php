<?php
error_reporting(E_ALL & ~E_NOTICE);
define('COMMENTS_PER_PAGE', 5);

if (preg_match('/^[1-9][0-9]*$/', $_GET['page'])) {
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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>コメント一覧</title>
</head>
<body>
<h1>コメント一覧</h1>
<ul>
	<?php foreach ($comments as $comment) : ?>
		<li><?php echo htmlspecialchars($comment['comment'],ENT_QUOTES, 'UTF-8'); ?></li>
	<?php endforeach; ?>
</ul>
<?php if ($page > 1) : ?>
	<a href="?page=<?php echo $page - 1 ?>">前</a>
<?php endif; ?>
<?php for ($i = 1; $i <= $totalPages; $i++) : ?>
	<?php if ($page == $i) : ?>
	<?php else : ?>
		<a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
	<?php endif; ?>
<?php endfor; ?>
<?php if ($page < $totalPages) : ?>
	<a href="?page=<?php echo $page + 1; ?>">次</a>
<?php endif; ?>
</body>
</html>
