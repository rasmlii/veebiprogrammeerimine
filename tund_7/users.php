<?php
	require("functions.php");
	
	if(!isset($_SESSION["userId"])){
		header("Location: index_1.php");
		exit();
	}

	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: index_1.php");
		exit();
	}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>kasutajad</title>
	</head>
	<body>
		<h1>Kasutajad</h1>
		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<ul>
		<li><a href="main.php">Pealeht</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	<hr>
	<div>
		<?php echo userlist(); ?>
	</div>
	
	</body>
</html>