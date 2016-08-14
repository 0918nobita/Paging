<?php
error_reporting(E_ALL & ~E_NOTICE);

try {
	$dbh = new PDO('sqlite:database.sqlite3');
	$sql = 'SELECT * FROM comments';
	$comments = array();
	foreach ($dbh->query($sql) as $row) {
		array_push($comments, $row);
	}
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
