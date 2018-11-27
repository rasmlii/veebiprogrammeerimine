<?php
	require("functions.php");
	
	$notice = null;
	
	if (isset($_POST["submitMessage"])){
		if ($_POST["message"] != "Siia sisesta oma sõnum ..." and !empty($_POST["message"])){
			$message = test_input($_POST["message"]);
			$notice = saveamsg($message);
		} else {
			$notice = "Palun kirjuta sõnum!";
		}
	}
	
	
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Anonüümse sõnumi lisamine</title>
</head>
<body>
	<div>
		<a href="main.php">
			<img src="../vp_picfiles/vp_logo_w135_h90.png" alt="VP logo">
		</a>
		<img src="../vp_picfiles/vp_banner.png" alt="VP banner">
	</div>
	<h1>Sõnumi lisamine</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsisevõetavat sisu.</p>
	<hr>
	<ul>
		<li><a href="index_1.php">Avaleht</a></li>
	</ul>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Sõnum (max 256 märki):</label>
		<br>
		<textarea rows="4" cols="64" name="message">Siia sisesta oma sõnum ...</textarea>
		<br>
		<input type="submit" name="submitMessage" value="Salvesta sõnum">
	</form>
	<hr>
	
	<p><?php echo $notice; ?></p>
	
	
	
	
	
	

	
	
	
</body>
</html>