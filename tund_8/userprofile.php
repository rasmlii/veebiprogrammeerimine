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
		if(isset($_POST["description"]) and isset($_POST["bgcolor"]) and isset($_POST["txtcolor"])){
			if(empty($_POST["description"])){
			 	$description = "Pole iseloomustust lisanud.";
			} else {
				$description = test_input($_POST["description"]);
			}
			$bgcolor = $_POST["bgcolor"];
			$txtcolor = $_POST["txtcolor"];
			$notice = saveprofile($description, $bgcolor, $txtcolor);
		}
	}

	// if(isset($_POST["submitProfile"])){

	// 	if(isset($_POST["description"])){
	// 		if(empty($_POST["description"])){
	// 			$description = "Pole iseloomustust lisanud.";
	// 		} else {
	// 			$description = test_input($_POST["description"]);
	// 		}
	// 	}

	// 	if(isset($_POST["bgcolor"])){
	// 		$bgcolor = $_POST["bgcolor"];
	// 	}

	// 	if(isset($_POST["txtcolor"])){
	// 		$txtcolor = $_POST["txtcolor"];
	// 	}

	// 	if(empty($descriptionerror)){
	// 		$notice = saveprofile($description, $bgcolor, $txtcolor);
	// 	}

	// }
	
	$data = userprofileload();
	$pagetitle = "Profiil";
	require("header.php");
?>


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