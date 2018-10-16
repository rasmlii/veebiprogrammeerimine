<?php
	require("functions.php");
	$notice = listallmessages();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Anonüümsete sõnumite lugemine</title>
</head>
<body>
	<h1>Sõnumid</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsisevõetavat sisu.</p>
	<hr>
	
	<?php
		echo $notice;
	?>
	
	
	
	
	
	

	
	
	
</body>
</html>