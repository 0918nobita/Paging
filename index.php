<?php
error_reporting(E_ALL & ~E_NOTICE);
define('COMMENTS_PER_PAGE', 5);
try {
	$dbh = new PDO('sqlite:database.sqlite3');
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
<?php foreach ($comments as $comment) : ?>
	<li><?php echo htmlspecialchars($comment['comment'],ENT_QUOTES,'UTF-8'); ?></li>
<?php endforeach; ?>
</body>
</html>
