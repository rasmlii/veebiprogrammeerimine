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
	
	$data = userprofileload();
	
	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>profiil</title>
	</head>
	<body>
		<h1>Profiil</h1>
		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Tere tulemast, <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<ul>
		<li><a href="main.php">Pealeht</a></li>	
	</ul>
	
	<form>
		<textarea rows="10" cols="80" name="description"><?php echo $data[0]; ?></textarea><br>
		<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $data[1]; ?>"><br>
		<label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $data[2]; ?>"><br>
		<input type="submit" name="submitProfile" value="Salvesta profiil">
	</form>
	
	
	
	</body>
</html>