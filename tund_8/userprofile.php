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
	
	$description = "";
	$bgcolor = "";
	$txtcolor = "";
	$notice = "";
	
	if(isset($_POST["submitProfile"])){

		if(isset($_POST["description"])){
			if(empty($_POST["description"])){
				$description = "Pole iseloomustust lisanud.";
			} else {
				$description = test_input($_POST["description"]);
			}
		}

		if(isset($_POST["bgcolor"])){
			$bgcolor = $_POST["bgcolor"];
		}

		if(isset($_POST["txtcolor"])){
			$txtcolor = $_POST["txtcolor"];
		}

		if(empty($descriptionerror)){
			$notice = saveprofile($description, $bgcolor, $txtcolor);
		}

	}
	
	$data = userprofileload();

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>profiil</title>
		
		<?php
		echo "<style>
	 		body{
				background-color: " .$data[1] ."; 
				color: " .$data[2] ."
			} 
		</style>";
		?>

	</head>
	<body>
		<h1>Profiil</h1>
		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Tere tulemast, <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<ul>
		<li><a href="main.php">Pealeht</a></li>	
	</ul>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<textarea rows="10" cols="80" name="description"><?php echo $data[0]; ?></textarea><br>
		<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $data[1]; ?>"><br>
		<label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $data[2]; ?>"><br>
		<input type="submit" name="submitProfile" value="Salvesta profiil">
	</form>
	
	<p><?php echo $notice; ?> </p>
	
	
	</body>
</html>